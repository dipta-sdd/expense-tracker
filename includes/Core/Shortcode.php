<?php

namespace ExpenseTracker\Core;

class Shortcode
{
    public function __construct()
    {
        add_shortcode('expense_tracker_groups', [$this, 'render_groups_shortcode']);
        add_shortcode('expense_tracker_group', [$this, 'render_single_group_shortcode']);
    }
    protected function check_permission($options = [])
    {
        $current_user = wp_get_current_user();
        $current_user_role = $current_user->roles;
        foreach ($options as $option) {
            if (in_array($option, $current_user_role)) {
                return true;
            }
        }
        return false;
    }
    /**
     * Render groups list shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_groups_shortcode($atts)
    {
        // Get view and group_id from URL
        error_log(json_encode($_GET));
        $view = isset($_GET['view']) ? sanitize_text_field($_GET['view']) : 'list';
        $view = isset($atts['view']) ? sanitize_text_field($atts['view']) :  $view;
        $style = isset($_GET['style']) ? sanitize_text_field($_GET['style']) : 'list';
        $style = isset($atts['style']) ? sanitize_text_field($atts['style']) : $style;
        $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;
        $limit = isset($atts['limit']) ? intval($atts['limit']) : $limit;
        $page = isset($_GET['offset']) ? intval($_GET['offset']) : 1;
        $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'name';
        $direction = isset($_GET['direction']) ? sanitize_text_field($_GET['direction']) : 'desc';
        $group_id = isset($_GET['group_id']) ? intval($_GET['group_id']) : 0;

        // If viewing a single group
        if ($view === 'group' && $group_id > 0) {
            return $this->render_single_group_shortcode(['id' => $group_id]);
        }

        // Get current user's groups

        $current_user_id = get_current_user_id();


        if (!$current_user_id) {
            return '<p>' . esc_html__('Please log in to view groups.', 'expense-tracker') . '</p>';
        }
        $current_user = wp_get_current_user();
        global $wpdb;
        $prefix = $wpdb->prefix;
        // $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$prefix}expense_tracker_groups as g WHERE g.status = 'Active'");
        $total_items = $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$prefix}expense_tracker_groups as g  INNER JOIN {$prefix}expense_tracker_group_members as gm ON (g.group_id = gm.group_id AND gm.user_id = %d) WHERE g.status = 'Active'", $current_user_id));
        $permission_check = $this->check_permission(get_option('expense_tracker_settings')['expense_tracker_group_creation_role']);

        $offset = ($page - 1) * $limit;
        $query = "SELECT * FROM
        ( SELECT 
            g.*,
            admin.display_name as admin_name,
            COUNT(DISTINCT gm.user_id) as member_count,
            CASE 
                WHEN g.admin_id = %d THEN 'admin'
                WHEN gm_current.user_id IS NOT NULL THEN gm_current.role
                ELSE 'none'
            END as user_role
        FROM {$prefix}expense_tracker_groups as g
        JOIN {$prefix}users as admin ON g.admin_id = admin.ID
        LEFT JOIN {$prefix}expense_tracker_group_members as gm ON g.group_id = gm.group_id
        LEFT JOIN {$prefix}expense_tracker_group_members as gm_current 
            ON g.group_id = gm_current.group_id AND gm_current.user_id = %d
        WHERE g.status = 'Active'
            AND (g.admin_id = %d OR gm_current.user_id IS NOT NULL) GROUP BY g.group_id ) AS basetable
        ORDER BY {$sort} {$direction}
        LIMIT {$limit} OFFSEt {$offset}
        ";



        $groups = $wpdb->get_results($wpdb->prepare(
            $query,
            $current_user_id,
            $current_user_id,
            $current_user_id
        ));

        $data = [
            'groups' => $groups,
            'style' => $style,
            'total_items' => $total_items,
            'limit' => $limit,
            'page' => $page,
            'sort' => $sort,
            'direction' => $direction,
            'permission_check' => $permission_check
        ];
        error_log(json_encode($data));
        ob_start();
        View::render('frontend/groups-' . $style, $data);
        return ob_get_clean();
    }

    /**
     * Render single group shortcode
     * 
     * @param array $atts Shortcode attributes
     * @return string
     */
    public function render_single_group_shortcode($atts)
    {

        $atts = shortcode_atts([
            'group_id' => isset($_GET['group_id']) ? intval($_GET['group_id']) : 0,
            'style' => 'default'
        ], $atts);
        if (!$atts['group_id']) {

            error_log('Single Group Shortcode Attributes Missing: ' . json_encode($atts));
            wp_safe_redirect(\ExpenseTracker\Core\Pages::get_page_url('groups'));
            exit;
            return '<p>' . esc_html__('Group ID is required .', 'expense-tracker') . '</p>';
        }

        global $wpdb;
        $prefix = $wpdb->prefix;
        $current_user_id = get_current_user_id();

        if (!$current_user_id) {
            return '<p>' . esc_html__('Please log in to view group details.', 'expense-tracker') . '</p>';
        }

        // Check user access
        $access_check = $wpdb->get_var($wpdb->prepare(
            "SELECT 1 FROM {$prefix}expense_tracker_groups g
            LEFT JOIN {$prefix}expense_tracker_group_members gm 
                ON g.group_id = gm.group_id AND gm.user_id = %d
            WHERE g.group_id = %d
            AND (g.admin_id = %d OR gm.user_id IS NOT NULL)",
            $current_user_id,
            $atts['group_id'],
            $current_user_id
        ));

        if (!$access_check) {
            return '<p>' . esc_html__('You do not have permission to view this group.', 'expense-tracker') . '</p>';
        }

        // Get group details
        $group = $wpdb->get_row($wpdb->prepare(
            "SELECT 
                g.*,
                admin.display_name as admin_name,
                COUNT(DISTINCT gm.user_id) as member_count,
                CASE 
                    WHEN g.admin_id = %d THEN 'admin'
                    ELSE 'member'
                END as user_role
            FROM {$prefix}expense_tracker_groups g
            JOIN {$prefix}users admin ON g.admin_id = admin.ID
            LEFT JOIN {$prefix}expense_tracker_group_members gm ON g.group_id = gm.group_id
            WHERE g.group_id = %d
            GROUP BY g.group_id",
            $current_user_id,
            $atts['group_id']
        ));
        if (!$group) {
            return '<p>' . esc_html__('Group not found.', 'expense-tracker') . '</p>';
        }
        $data = [
            'group' => $group,
            'style' => $atts['style']
        ];

        ob_start();
        View::render('frontend/single-group', $data);
        return ob_get_clean();
    }
}
<?php

namespace ExpenseTracker\Admin;

use ExpenseTracker\Core\View;

class Settings
{
    public function add_assets()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_assets'));
    }
    /**
     * Enqueue admin-specific styles and scripts
     */
    public function enqueue_admin_assets($hook)
    {
        // Only load on expense tracker pages
        if (strpos($hook, 'expense-tracker') !== false) {
            wp_enqueue_style(
                'expense-tracker-admin',
                EXPENSE_TRACKER_URL . 'assets/css/admin.css',
                array(),
                EXPENSE_TRACKER_VERSION
            );

            wp_enqueue_script(
                'expense-tracker-admin',
                EXPENSE_TRACKER_URL . 'assets/js/admin.js',
                array('jquery'),
                EXPENSE_TRACKER_VERSION,
                true
            );
        }
    }
    public static function register_admin_menu()
    {
        // Add the main Expense Tracker menu page
        add_menu_page(
            __('Expense Tracker', 'expense-tracker'),
            __('Expense Tracker', 'expense-tracker'),
            'manage_options',
            'expense-tracker',
            array(View::class, 'admin_dashboard_page'),
            'dashicons-money',
            20
        );

        // Add submenu pages
        add_submenu_page(
            'expense-tracker',
            __('Dashboard', 'expense-tracker'),
            __('Dashboard', 'expense-tracker'),
            'manage_options',
            'expense-tracker',
            array(View::class, 'admin_dashboard_page')
        );

        add_submenu_page(
            'expense-tracker',
            __('Expenses', 'expense-tracker'),
            __('Expenses', 'expense-tracker'),
            'edit_posts',
            'expense-tracker-expenses',
            array(View::class, 'admin_expenses_page')
        );
        add_submenu_page(
            'expense-tracker',
            __('Groups', 'expense-tracker'),
            __('Groups', 'expense-tracker'),
            'edit_posts',
            'expense-tracker-groups',
            array(View::class, 'admin_groups_page')
        );
        // Add the settings submenu page
        add_submenu_page(
            'expense-tracker',  // Parent slug (same as the main menu slug)
            __('Expense Tracker Settings', 'expense-tracker'),
            __('Settings', 'expense-tracker'),
            'manage_options',
            'expense-tracker-settings',
            array(View::class, 'admin_settings_page')
        );
    }
    public static function display_dashboard_page()
    {
        require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Menu/dashboard_page.php';
    }
    public static function display_expenses_page()
    {
        require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Menu/expenses_page.php';
    }
    public static function display_groups_page()
    {
        require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Menu/groups_page.php';
    }
    public static function display_settings_page()
    {
        require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Menu/setting_page.php';
    }
    public static function expense_tracker_settings_init()
    {
        register_setting(
            'expense_tracker_settings_group',
            'expense_tracker_settings',
            array(__CLASS__, 'expense_tracker_settings_sanitize') // Use __CLASS__
        );

        add_settings_section(
            'expense_tracker_expense_permissions_section',
            'Expense Permissions',
            array(__CLASS__, 'expense_tracker_expense_permissions_section_callback'), // Use __CLASS__
            'expense-tracker-settings'
        );

        add_settings_field(
            'expense_tracker_add_expenses_role',
            'Allow adding expenses for:',
            array(__CLASS__, 'expense_tracker_add_expenses_role_render'), // Use __CLASS__
            'expense-tracker-settings',
            'expense_tracker_expense_permissions_section'
        );

        add_settings_field(
            'expense_tracker_edit_expenses_role',
            'Allow editing expenses for:',
            array(__CLASS__, 'expense_tracker_edit_expenses_role_render'), // Use __CLASS__
            'expense-tracker-settings',
            'expense_tracker_expense_permissions_section'
        );

        // Add Group Settings Section
        add_settings_section(
            'expense_tracker_group_settings_section',
            __('Group Settings', 'expense-tracker'),
            array(__CLASS__, 'group_settings_section_callback'),
            'expense-tracker-settings'
        );

        add_settings_field(
            'expense_tracker_group_creation_role',
            __('Allow group creation for:', 'expense-tracker'),
            array(__CLASS__, 'group_creation_role_render'),
            'expense-tracker-settings',
            'expense_tracker_group_settings_section'
        );

        // Add Budget Settings Section
        add_settings_section(
            'expense_tracker_budget_settings_section',
            __('Budget Settings', 'expense-tracker'),
            array(__CLASS__, 'budget_settings_section_callback'),
            'expense-tracker-settings'
        );

        add_settings_field(
            'expense_tracker_enable_email_alerts',
            __('Enable Budget Email Alerts', 'expense-tracker'),
            array(__CLASS__, 'email_alerts_render'),
            'expense-tracker-settings',
            'expense_tracker_budget_settings_section'
        );

        // Add Receipt Settings Section
        add_settings_section(
            'expense_tracker_receipt_settings_section',
            __('Receipt Settings', 'expense-tracker'),
            array(__CLASS__, 'receipt_settings_section_callback'),
            'expense-tracker-settings'
        );

        add_settings_field(
            'expense_tracker_max_receipt_size',
            __('Maximum Receipt Size (MB)', 'expense-tracker'),
            array(__CLASS__, 'max_receipt_size_render'),
            'expense-tracker-settings',
            'expense_tracker_receipt_settings_section'
        );

        // ... add more sections and fields for other permissions ...
    }
    public static function expense_tracker_expense_permissions_section_callback()
    {
        echo __('Configure which user roles can add expenses.', 'expense-tracker');
    }

    public static function expense_tracker_add_expenses_role_render()
    {
        $options = get_option('expense_tracker_settings');
        $selected_roles = isset($options['expense_tracker_add_expenses_role']) ? $options['expense_tracker_add_expenses_role'] : array();

        $roles = get_editable_roles();
        foreach ($roles as $role_name => $role_info) { ?>
            <input type="checkbox" name="expense_tracker_settings[expense_tracker_add_expenses_role][]"
                value="<?php echo esc_attr($role_name); ?>" <?php checked(in_array($role_name, $selected_roles), true); ?>>
            <?php echo $role_info['name']; ?><br>
        <?php
        }
    }
    public static function expense_tracker_edit_expenses_role_render()
    {
        $options = get_option('expense_tracker_settings');
        $selected_roles = isset($options['expense_tracker_edit_expenses_role']) ? $options['expense_tracker_edit_expenses_role'] : array();

        $roles = get_editable_roles();
        foreach ($roles as $role_name => $role_info) {
        ?>
            <input type="checkbox" name="expense_tracker_settings[expense_tracker_edit_expenses_role][]"
                value="<?php echo esc_attr($role_name); ?>" <?php checked(in_array($role_name, $selected_roles), true); ?>>
            <?php echo esc_html($role_info['name']); ?><br>
        <?php
        }
    }

    // Add new callback methods
    public static function group_settings_section_callback()
    {
        echo __('Configure group-related settings and permissions.', 'expense-tracker');
    }

    public static function group_creation_role_render()
    {
        $options = get_option('expense_tracker_settings');
        $selected_roles = isset($options['expense_tracker_group_creation_role'])
            ? $options['expense_tracker_group_creation_role']
            : array('administrator');

        $roles = get_editable_roles();
        foreach ($roles as $role_name => $role_info) {
        ?>
            <input type="checkbox" name="expense_tracker_settings[expense_tracker_group_creation_role][]"
                value="<?php echo esc_attr($role_name); ?>" <?php checked(in_array($role_name, $selected_roles), true); ?>>
            <?php echo esc_html($role_info['name']); ?><br>
        <?php
        }
    }

    public static function email_alerts_render()
    {
        $options = get_option('expense_tracker_settings');
        $enabled = isset($options['expense_tracker_enable_email_alerts'])
            ? $options['expense_tracker_enable_email_alerts']
            : true;
        ?>
        <input type="checkbox" name="expense_tracker_settings[expense_tracker_enable_email_alerts]" value="1"
            <?php checked($enabled, true); ?>>
    <?php
    }

    public static function max_receipt_size_render()
    {
        $options = get_option('expense_tracker_settings');
        $max_size = isset($options['expense_tracker_max_receipt_size'])
            ? $options['expense_tracker_max_receipt_size']
            : 5;
    ?>
        <input type="number" name="expense_tracker_settings[expense_tracker_max_receipt_size]"
            value="<?php echo esc_attr($max_size); ?>" min="1" max="20">
<?php
    }

    public static function expense_tracker_settings_sanitize($input)
    {
        $sanitized = array();

        // Sanitize role selections
        $role_fields = array(
            'expense_tracker_add_expenses_role',
            'expense_tracker_edit_expenses_role',
            'expense_tracker_group_creation_role'
        );

        foreach ($role_fields as $field) {
            if (isset($input[$field]) && is_array($input[$field])) {
                $sanitized[$field] = array_map('sanitize_text_field', $input[$field]);
            }
        }

        // Sanitize email alerts
        $sanitized['expense_tracker_enable_email_alerts'] =
            isset($input['expense_tracker_enable_email_alerts']) ? true : false;

        // Sanitize max receipt size
        $sanitized['expense_tracker_max_receipt_size'] =
            isset($input['expense_tracker_max_receipt_size'])
            ? absint($input['expense_tracker_max_receipt_size'])
            : 5;

        return $sanitized;
    }
}

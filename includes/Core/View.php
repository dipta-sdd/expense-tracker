<?php

namespace ExpenseTracker\Core;

use NinjaDB\BaseModel;

class View
{
    private static $view_path = EXPENSE_TRACKER_PATH . 'views/';

    public static function render($view, $data = [])
    {
        $view_file = self::get_view_file($view);

        if (!file_exists($view_file)) {
            throw new \Exception("View file not found: {$view_file}");
        }

        extract($data);
        include $view_file;
    }

    private static function get_view_file($view)
    {
        if (is_array($view)) {
            return self::$view_path . rtrim($view[0], '.php') . '.php';
        }
        return self::$view_path . rtrim($view, '.php') . '.php';
    }

    public static function admin_expenses_page()
    {
        $data = [
            'expenses' => [], // Get from ExpenseController
        ];
        self::render('admin/expenses_page', $data);
    }

    public static function admin_dashboard_page()
    {
        self::render('admin/dashboard_page');
    }

    public static function admin_groups_page()
    {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';
        $group_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

        global $wpdb;
        $prefix = $wpdb->prefix;



        if ($action === 'new' || $action === 'edit') {
            self::render('admin/groups_form');
        } else {
            $total_items = $wpdb->get_var("SELECT COUNT(*) FROM {$prefix}expense_tracker_groups");
            $per_page = isset($_GET['per_page']) ? intval($_GET['per_page']) : 10;
            $page = isset($_GET['p']) ? intval($_GET['p']) : 1;
            $sort = isset($_GET['sort']) ? sanitize_text_field($_GET['sort']) : 'expense';
            $direction = isset($_GET['direction']) ? sanitize_text_field($_GET['direction']) : 'desc';
            $offset = ($page - 1) * $per_page;
            $groups = $wpdb->get_results("SELECT mygroups.*, COUNT(members.user_id) as members, SUM(expenses.amount) as expense FROM {$prefix}expense_tracker_groups as mygroups
            LEFT JOIN {$prefix}expense_tracker_group_members as members ON mygroups.group_id = members.group_id
            LEFT JOIN {$prefix}expense_tracker_expenses as expenses ON mygroups.group_id = expenses.group_id
            GROUP BY mygroups.group_id
            ORDER BY {$sort} {$direction}
            LIMIT {$per_page} OFFSEt {$offset}");
            self::render('admin/groups_page', ['groups' => $groups, 'total_items' => $total_items, 'per_page' => $per_page, 'page' => $page, 'sort' => $sort, 'direction' => $direction]);
        }
    }

    public static function admin_settings_page()
    {
        self::render('admin/settings_page');
    }
}

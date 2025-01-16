<?php

namespace ExpenseTracker\Modules;

class Expenses
{
    private $table_name;
    private $menu_slug = 'expense-tracker';

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'expense_tracker_expenses';
        $this->init();
    }

    private function init()
    {
        add_action('admin_menu', [$this, 'registerAdminMenus']);
    }

    public function registerAdminMenus()
    {
        // Main menu
        add_menu_page(
            __('Expense Tracker', 'expense-tracker'),
            __('Expense Tracker', 'expense-tracker'),
            'manage_options',
            $this->menu_slug,
            [$this, 'renderMainPage'],
            'dashicons-money-alt',
            30
        );

        // Submenu pages
        add_submenu_page(
            $this->menu_slug,
            __('All Expenses', 'expense-tracker'),
            __('All Expenses', 'expense-tracker'),
            'manage_options',
            $this->menu_slug,
            [$this, 'renderMainPage']
        );

        add_submenu_page(
            $this->menu_slug,
            __('Add New Expense', 'expense-tracker'),
            __('Add New', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-add-new',
            [$this, 'renderAddNewPage']
        );

        // Additional submenu pages
        add_submenu_page(
            $this->menu_slug,
            __('Categories', 'expense-tracker'),
            __('Categories', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-categories',
            [$this, 'renderCategoriesPage']
        );

        add_submenu_page(
            $this->menu_slug,
            __('Reports', 'expense-tracker'),
            __('Reports', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-reports',
            [$this, 'renderReportsPage']
        );
    }

    public function createExpense($data)
    {
        global $wpdb;

        $defaults = [
            'user_id' => get_current_user_id(),
            'amount' => 0,
            'description' => '',
            'category_id' => 0,
            'date' => current_time('mysql'),
            'status' => 'pending',
            'created_at' => current_time('mysql'),
        ];

        $data = wp_parse_args($data, $defaults);

        // Sanitize data
        $data = $this->sanitizeExpenseData($data);

        // Validate data
        if (!$this->validateExpenseData($data)) {
            return new \WP_Error('validation_failed', __('Invalid expense data', 'expense-tracker'));
        }

        $inserted = $wpdb->insert(
            $this->table_name,
            $data,
            [
                '%d', // user_id
                '%f', // amount
                '%s', // description
                '%d', // category_id
                '%s', // date
                '%s', // status
                '%s', // created_at
            ]
        );

        if (!$inserted) {
            return new \WP_Error('db_insert_error', __('Could not create expense', 'expense-tracker'));
        }

        return $wpdb->insert_id;
    }

    public function getExpense($id)
    {
        global $wpdb;

        $expense = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM {$this->table_name} WHERE id = %d",
                $id
            ),
            ARRAY_A
        );

        return $expense;
    }

    public function getExpenses($args = [])
    {
        global $wpdb;

        $defaults = [
            'per_page' => 10,
            'page' => 1,
            'user_id' => 0,
            'category_id' => 0,
            'status' => '',
            'orderby' => 'date',
            'order' => 'DESC',
        ];

        $args = wp_parse_args($args, $defaults);
        $offset = ($args['page'] - 1) * $args['per_page'];

        $where = "WHERE 1=1";
        if ($args['user_id']) {
            $where .= $wpdb->prepare(" AND user_id = %d", $args['user_id']);
        }
        if ($args['category_id']) {
            $where .= $wpdb->prepare(" AND category_id = %d", $args['category_id']);
        }
        if ($args['status']) {
            $where .= $wpdb->prepare(" AND status = %s", $args['status']);
        }

        $sql = "SELECT * FROM {$this->table_name} 
                {$where} 
                ORDER BY {$args['orderby']} {$args['order']}
                LIMIT %d OFFSET %d";

        return $wpdb->get_results(
            $wpdb->prepare($sql, $args['per_page'], $offset),
            ARRAY_A
        );
    }

    private function sanitizeExpenseData($data)
    {
        return [
            'user_id' => absint($data['user_id']),
            'amount' => floatval($data['amount']),
            'description' => sanitize_text_field($data['description']),
            'category_id' => absint($data['category_id']),
            'date' => sanitize_text_field($data['date']),
            'status' => sanitize_text_field($data['status']),
            'created_at' => sanitize_text_field($data['created_at']),
        ];
    }

    private function validateExpenseData($data)
    {
        if ($data['amount'] <= 0) {
            return false;
        }
        if (empty($data['description'])) {
            return false;
        }
        if ($data['category_id'] <= 0) {
            return false;
        }
        return true;
    }

    public function renderMainPage()
    {
        $view = new \ExpenseTracker\Core\View();
        $view->render('admin/expenses/list');
    }

    public function renderAddNewPage()
    {
        $view = new \ExpenseTracker\Core\View();
        $view->render('admin/expenses/new');
    }

    public function renderCategoriesPage()
    {
        $view = new \ExpenseTracker\Core\View();
        $view->render('admin/categories/list');
    }

    public function renderReportsPage()
    {
        $view = new \ExpenseTracker\Core\View();
        $view->render('admin/reports/index');
    }
}

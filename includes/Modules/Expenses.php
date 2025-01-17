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
        $data = $this->sanitizeExpenseData($data);
        $data['created_by'] = get_current_user_id();
        if (!$this->validateExpenseData($data)) {
            return new \WP_Error('invalid_data', __('Invalid expense data.', 'expense-tracker'));
        }

        $result = $wpdb->insert($this->table_name, $data);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to create expense.', 'expense-tracker'), ['status' => 500]);
        }

        $id = $wpdb->insert_id;
        return $this->getExpense($id);
    }

    public function updateExpense($id, $data)
    {
        global $wpdb;
        $data = $this->sanitizeExpenseData($data);
        if (!$this->validateExpenseData($data)) {
            return new \WP_Error('invalid_data', __('Invalid expense data.', 'expense-tracker'));
        }

        $result = $wpdb->update($this->table_name, $data, ['id' => $id]);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to update expense.', 'expense-tracker'), ['status' => 500]);
        }

        return $this->getExpense($id);
    }

    public function deleteExpense($id)
    {
        global $wpdb;
        $result = $wpdb->delete($this->table_name, ['id' => $id]);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to delete expense.', 'expense-tracker'), ['status' => 500]);
        }

        return true;
    }

    public function getExpense($id)
    {
        global $wpdb;

        $query = $wpdb->prepare("SELECT e.*, c.name as category_name, u1.display_name as created_by_name, u2.display_name as updated_by_name
                                FROM {$this->table_name} e
                                LEFT JOIN {$wpdb->prefix}expense_tracker_categories c ON e.category_id = c.id
                                LEFT JOIN {$wpdb->users} u1 ON e.created_by = u1.ID
                                LEFT JOIN {$wpdb->users} u2 ON e.updated_by = u2.ID
                                WHERE e.id = %d", $id);
        return $wpdb->get_row($query, ARRAY_A);
    }

    public function getExpenses($args = [])
    {
        global $wpdb;
        $where = 'WHERE 1=1';
        $order_by = 'e.date DESC';
        $params = [];

        if (!empty($args['category_id'])) {
            $where .= ' AND e.category_id = %d';
            $params[] = $args['category_id'];
        }

        if (!empty($args['status'])) {
            $where .= ' AND e.status = %s';
            $params[] = $args['status'];
        }

        if (!empty($args['sort_by'])) {
            switch ($args['sort_by']) {
                case 'date_asc':
                    $order_by = 'e.date ASC';
                    break;
                case 'amount_asc':
                    $order_by = 'e.amount ASC';
                    break;
                case 'amount_desc':
                    $order_by = 'e.amount DESC';
                    break;
                default:
                    $order_by = 'e.date DESC';
                    break;
            }
        }

        $sql = "SELECT e.*, c.name as category_name, u1.display_name as created_by_name, u2.display_name as updated_by_name
                                FROM {$this->table_name} e
                                LEFT JOIN {$wpdb->prefix}expense_tracker_categories c ON e.category_id = c.id
                                LEFT JOIN {$wpdb->users} u1 ON e.created_by = u1.ID
                                LEFT JOIN {$wpdb->users} u2 ON e.updated_by = u2.ID
                                {$where} ORDER BY {$order_by}";

        $query = $wpdb->prepare($sql, $params);

        return $wpdb->get_results($query, ARRAY_A);
    }

    private function sanitizeExpenseData($data)
    {
        $current_user = wp_get_current_user();
        return [
            'amount' => floatval($data['amount']),
            'description' => sanitize_text_field($data['description']),
            'category_id' => intval($data['category_id']),
            'date' => sanitize_text_field($data['date']),
            'status' => sanitize_text_field($data['status'] ?? 'pending'),
            'updated_by' => $current_user->ID,
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ];
    }

    private function validateExpenseData($data)
    {
        if (empty($data['amount']) || empty($data['description']) || empty($data['category_id']) || empty($data['date'])) {
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
        $view->render('admin/categories/list', ['categories' => $categories]);
    }

    public function renderReportsPage()
    {
        $view = new \ExpenseTracker\Core\View();
        $view->render('admin/reports/index');
    }
}

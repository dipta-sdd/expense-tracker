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
    }




    /**
     * Create a new expense.
     *
     * @param array $data The expense data.
     * @return array|WP_Error The created expense or an error object.
     */
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

    /**
     * Update an existing expense.
     *
     * @param int $id The ID of the expense to update.
     * @param array $data The updated expense data.
     * @return array|WP_Error The updated expense or an error object.
     */
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

    /**
     * Delete an existing expense.
     *
     * @param int $id The ID of the expense to delete.
     * @return bool|WP_Error True if the expense is deleted successfully, false otherwise.
     */
    public function deleteExpense($id)
    {
        global $wpdb;
        $result = $wpdb->delete($this->table_name, ['id' => $id]);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to delete expense.', 'expense-tracker'), ['status' => 500]);
        }

        return true;
    }

    /**
     * Get a single expense by its ID.
     *
     * @param int $id The ID of the expense to retrieve.
     * @return array|null The expense data or null if not found.
     */
    public function getExpense($id)
    {
        global $wpdb;

        $query = $wpdb->prepare(
            "SELECT e.*, c.name as category_name, u1.display_name as created_by_name, u2.display_name as updated_by_name
            FROM {$this->table_name} e
            LEFT JOIN {$wpdb->prefix}expense_tracker_categories c ON e.category_id = c.id
            LEFT JOIN {$wpdb->users} u1 ON e.created_by = u1.ID
            LEFT JOIN {$wpdb->users} u2 ON e.updated_by = u2.ID
            WHERE e.id = %d",
            $id
        );
        return $wpdb->get_row($query, ARRAY_A);
    }

    /**
     * Get a list of expenses.
     *
     * @param array $args The query arguments.
     * @return array The list of expenses.
     */
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

        if (!empty($args['start_date'])) {
            $where .= ' AND e.date >= %s';
            $params[] = $args['start_date'];
        }

        if (!empty($args['end_date'])) {
            $where .= ' AND e.date <= %s';
            $params[] = $args['end_date'];
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

    /**
     * Sanitize the expense data.
     *
     * @param array $data The expense data.
     * @return array The sanitized expense data.
     */
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

    /**
     * Validate the expense data.
     *
     * @param array $data The expense data.
     * @return bool True if the data is valid, false otherwise.
     */
    private function validateExpenseData($data)
    {
        if (empty($data['amount']) || empty($data['description']) || empty($data['category_id']) || empty($data['date'])) {
            return false;
        }
        return true;
    }
}

<?php

namespace ExpenseTracker\Modules;

class Categories
{
    private $table_name;
    private $expense_table;
    private $user_table;

    public function __construct()
    {
        global $wpdb;
        $this->table_name = $wpdb->prefix . 'expense_tracker_categories';
        $this->expense_table = $wpdb->prefix . 'expense_tracker_expenses';
        $this->user_table = $wpdb->prefix . 'users';
    }

    /**
     * Create a new category.
     *
     * @param array $data The category data.
     * @return int|WP_Error The ID of the created category or an error object.
     */
    public function createCategory($data)
    {
        global $wpdb;
        $data = $this->sanitizeCategoryData($data);
        $data['created_by'] = get_current_user_id();
        $data['updated_by'] = get_current_user_id();
        if (!$this->validateCategoryData($data)) {
            return new \WP_Error('invalid_data', __('Invalid category data.', 'expense-tracker'));
        }

        $result = $wpdb->insert($this->table_name, $data);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to create category.', 'expense-tracker'), ['status' => 500]);
        }

        return $wpdb->insert_id;
    }

    /**
     * Update an existing category.
     *
     * @param int $id The ID of the category to update.
     * @param array $data The updated category data.
     * @return bool|WP_Error True if the category is updated successfully, false otherwise.
     */
    public function updateCategory($id, $data)
    {
        global $wpdb;
        $data = $this->sanitizeCategoryData($data);
        $data['updated_by'] = get_current_user_id();
        if (!$this->validateCategoryData($data)) {
            return new \WP_Error('invalid_data', __('Invalid category data.', 'expense-tracker'));
        }

        $result = $wpdb->update($this->table_name, $data, ['id' => $id]);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to update category.', 'expense-tracker'), ['status' => 500]);
        }

        return true;
    }

    /**
     * Delete an existing category.
     *
     * @param int $id The ID of the category to delete.
     * @return bool|WP_Error True if the category is deleted successfully, false otherwise.
     */
    public function deleteCategory($id)
    {
        global $wpdb;
        $result = $wpdb->delete($this->table_name, ['id' => $id]);

        if ($result === false) {
            return new \WP_Error('db_error', __('Failed to delete category.', 'expense-tracker'), ['status' => 500]);
        }

        return true;
    }

    /**
     * Get a single category by its ID.
     *
     * @param int $id The ID of the category to retrieve.
     * @return array|null The category data or null if not found.
     */
    public function getCategory($id)
    {
        global $wpdb;
        $query = $wpdb->prepare("SELECT categories.*, SUM(expenses.amount) as total_expenses, COUNT(expenses.id) as total_expenses_count, created_by.display_name as created_by, updated_by.display_name as updated_by FROM {$this->table_name} as categories LEFT JOIN {$this->user_table} as created_by ON created_by.id = categories.created_by LEFT JOIN {$this->user_table} as updated_by ON updated_by.id = categories.updated_by LEFT JOIN {$this->expense_table} as expenses ON expenses.category_id = categories.id WHERE categories.id = %d", $id);
        $category = $wpdb->get_row($query, ARRAY_A);
        $category['created_at'] = wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($category['created_at']));
        $category['updated_at'] = wp_date(get_option('date_format') . ' ' . get_option('time_format'), strtotime($category['updated_at']));
        return $category;
    }

    /**
     * Get a list of categories.
     *
     * @return array The list of categories.
     */
    public function getCategories()
    {
        global $wpdb;
        $query = "SELECT categories.*, SUM(expenses.amount) as total_expenses, COUNT(expenses.id) as total_expenses_count, created_by.display_name as created_by, updated_by.display_name as updated_by FROM {$this->table_name} as categories LEFT JOIN {$this->user_table} as created_by ON created_by.id = categories.created_by LEFT JOIN {$this->user_table} as updated_by ON updated_by.id = categories.updated_by LEFT JOIN {$this->expense_table} as expenses ON expenses.category_id = categories.id GROUP BY categories.id";
        return $wpdb->get_results($query, ARRAY_A);
    }

    /**
     * Sanitize the category data.
     *
     * @param array $data The category data.
     * @return array The sanitized category data.
     */
    private function sanitizeCategoryData($data)
    {
        return [
            'name' => sanitize_text_field($data['name']),
            'description' => sanitize_textarea_field($data['description']),
            'budget' => floatval($data['budget']),
        ];
    }

    /**
     * Validate the category data.
     *
     * @param array $data The category data.
     * @return bool True if the data is valid, false otherwise.
     */
    private function validateCategoryData($data)
    {
        if (empty($data['name'])) {
            return false;
        }
        return true;
    }
}

<?php

namespace ExpenseTracker\Core;

class FormHandler
{
    public function __construct()
    {
        add_action('admin_post_create_expense', [$this, 'handleCreateExpense']);
        add_action('admin_post_update_expense', [$this, 'handleUpdateExpense']);
        add_action('admin_post_delete_expense', [$this, 'handleDeleteExpense']);
    }

    public function handleCreateExpense()
    {
        check_admin_referer('create_expense', 'expense_nonce');

        if (!current_user_can('submit_expenses')) {
            wp_die(__('You do not have permission to create expenses.', 'expense-tracker'));
        }

        $expense_data = $this->sanitizeExpenseData($_POST);
        $result = expense_tracker_init()->getModule('expenses')->createExpense($expense_data);

        if (is_wp_error($result)) {
            $this->redirectBack('error', $result->get_error_message());
        }

        $this->redirectBack('success', __('Expense created successfully.', 'expense-tracker'));
    }

    public function handleUpdateExpense()
    {
        check_admin_referer('update_expense', 'expense_nonce');

        if (!current_user_can('manage_expenses')) {
            wp_die(__('You do not have permission to update expenses.', 'expense-tracker'));
        }

        $expense_id = intval($_POST['expense_id']);
        $expense_data = $this->sanitizeExpenseData($_POST);
        $result = expense_tracker_init()->getModule('expenses')->updateExpense($expense_id, $expense_data);

        if (is_wp_error($result)) {
            $this->redirectBack('error', $result->get_error_message());
        }

        $this->redirectBack('success', __('Expense updated successfully.', 'expense-tracker'));
    }

    public function handleDeleteExpense()
    {
        check_admin_referer('delete_expense', 'expense_nonce');

        if (!current_user_can('manage_expenses')) {
            wp_die(__('You do not have permission to delete expenses.', 'expense-tracker'));
        }

        $expense_id = intval($_POST['expense_id']);
        $result = expense_tracker_init()->getModule('expenses')->deleteExpense($expense_id);

        if (is_wp_error($result)) {
            $this->redirectBack('error', $result->get_error_message());
        }

        $this->redirectBack('success', __('Expense deleted successfully.', 'expense-tracker'));
    }

    private function sanitizeExpenseData($data)
    {
        return [
            'amount' => floatval($data['amount']),
            'description' => sanitize_text_field($data['description']),
            'category_id' => intval($data['category_id']),
            'date' => sanitize_text_field($data['date']),
            'status' => sanitize_text_field($data['status'] ?? 'pending'),
            'created_at' => current_time('mysql'),
            'updated_at' => current_time('mysql')
        ];
    }

    private function redirectBack($type, $message)
    {
        $url = add_query_arg([
            'page' => $_REQUEST['page'] ?? 'expense-tracker',
            'message' => urlencode($message),
            'type' => $type
        ], admin_url('admin.php'));

        wp_redirect($url);
        exit;
    }
}

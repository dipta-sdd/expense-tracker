<?php

namespace ExpenseTracker\Controller;

use ExpenseTracker\Core\Request;
use WP_REST_Response;
use WP_Error;

class ExpenseController
{
    private $expenses;

    public function __construct()
    {
        $this->expenses = expense_tracker_init()->getModule('expenses');
    }

    public function index(Request $request)
    {
        if (!current_user_can('view_expenses') && !current_user_can('manage_expenses')) {
            return new WP_Error('forbidden', 'You do not have permission to view expenses', ['status' => 403]);
        }

        $args = [
            'per_page' => $request->get('per_page', 10),
            'page' => $request->get('page', 1),
            'user_id' => $request->get('user_id', 0),
            'category_id' => $request->get('category_id', 0),
            'status' => $request->get('status', ''),
        ];

        $expenses = $this->expenses->getExpenses($args);
        return new WP_REST_Response($expenses, 200);
    }

    public function store(Request $request)
    {
        if (!current_user_can('submit_expenses')) {
            return new WP_Error('forbidden', 'You do not have permission to create expenses', ['status' => 403]);
        }

        $expense_id = $this->expenses->createExpense($request->all());

        if (is_wp_error($expense_id)) {
            return new WP_Error('create_failed', $expense_id->get_error_message(), ['status' => 400]);
        }

        $expense = $this->expenses->getExpense($expense_id);
        return new WP_REST_Response($expense, 201);
    }

    public function show(Request $request, $id)
    {
        if (!current_user_can('view_expenses') && !current_user_can('manage_expenses')) {
            return new WP_Error('forbidden', 'You do not have permission to view expenses', ['status' => 403]);
        }

        $expense = $this->expenses->getExpense($id);

        if (!$expense) {
            return new WP_Error('not_found', 'Expense not found', ['status' => 404]);
        }

        return new WP_REST_Response($expense, 200);
    }

    public function update(Request $request, $id)
    {
        if (!current_user_can('manage_expenses')) {
            return new WP_Error('forbidden', 'You do not have permission to update expenses', ['status' => 403]);
        }

        $result = $this->expenses->updateExpense($id, $request->all());

        if (is_wp_error($result)) {
            return new WP_Error('update_failed', $result->get_error_message(), ['status' => 400]);
        }

        $expense = $this->expenses->getExpense($id);
        return new WP_REST_Response($expense, 200);
    }

    public function destroy(Request $request, $id)
    {
        if (!current_user_can('manage_expenses')) {
            return new WP_Error('forbidden', 'You do not have permission to delete expenses', ['status' => 403]);
        }

        $result = $this->expenses->deleteExpense($id);

        if (is_wp_error($result)) {
            return new WP_Error('delete_failed', $result->get_error_message(), ['status' => 400]);
        }

        return new WP_REST_Response(null, 204);
    }
}

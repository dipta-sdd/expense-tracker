<?php

namespace ExpenseTracker\Controller;

use ExpenseTracker\Core\Request;

class ExpenseController
{
    private $expenses;

    public function __construct()
    {
        $this->expenses = expense_tracker_init()->getModule('expenses');
    }

    /**
     * Get all expenses
     *
     * @param Request $request
     * @return \WP_REST_Response
     */
    public function index(Request $request)
    {
        $params = $request->all();
        $expenses = $this->expenses->getExpenses($params);
        return rest_ensure_response($expenses);
    }

    /**
     * Store a new expense
     *
     * @param Request $request
     * @return \WP_Error|\WP_REST_Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'date' => 'required|date',
        ]);
        if (!$request->isValid()) {
            return new \WP_Error('validation_error', $request->getErrors(), ['status' => 400]);
        }
        $data = $request->all();
        $result = $this->expenses->createExpense($data);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response($result);
    }

    /**
     * Get a single expense
     *
     * @param int $id
     * @return \WP_REST_Response
     */
    public function show($id)
    {
        $expense = $this->expenses->getExpense($id);
        return rest_ensure_response($expense);
    }

    /**
     * Update an expense
     *
     * @param Request $request
     * @param int $id
     * @return \WP_Error|\WP_REST_Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|integer',
            'date' => 'required|date',
        ]);
        if (!$request->isValid()) {
            return new \WP_Error('validation_error', $request->getErrors(), ['status' => 400]);
        }
        $data = $request->all();
        $result = $this->expenses->updateExpense($id, $data);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response($result);
    }

    /**
     * Delete an expense
     *
     * @param Request $request
     * @param int $id
     * @return \WP_Error|\WP_REST_Response
     */
    public function destroy(Request $request, $id)
    {
        $result = $this->expenses->deleteExpense($id);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response(['message' => __('Expense deleted successfully.', 'expense-tracker')]);
    }

    /**
     * Validate the request data
     *
     * @param array $data
     * @return void
     */
    private function validateRequest($data)
    {
        // Implementation for validating request data
    }

    /**
     * Format the response data
     *
     * @param array $expense
     * @return void
     */
    private function formatResponse($expense)
    {
        // Implementation for formatting response data
    }
}

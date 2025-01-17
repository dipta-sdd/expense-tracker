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

    public function index(Request $request)
    {
        $params = $request->all();
        $expenses = $this->expenses->getExpenses($params);
        return rest_ensure_response($expenses);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $result = $this->expenses->createExpense($data);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response($result);
    }

    public function show(Request $request, $id)
    {
        $expense = $this->expenses->getExpense($id);
        return rest_ensure_response($expense);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->expenses->updateExpense($id, $data);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response($result);
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->expenses->deleteExpense($id);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response(['message' => __('Expense deleted successfully.', 'expense-tracker')]);
    }

    private function validateRequest($data)
    {
        // Implementation for validating request data
    }

    private function formatResponse($expense)
    {
        // Implementation for formatting response data
    }
}

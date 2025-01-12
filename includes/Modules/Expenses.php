<?php

namespace ExpenseTracker\Modules;

use NinjaDB\BaseModel;

class Expenses
{
    private $db;
    protected $table = 'expense_tracker_expenses';

    public function __construct()
    {
    }

    public function getAllExpenses($user_id, $filters = [])
    {
    }

    public function createExpense($data)
    {
    }

    public function updateExpense($expense_id, $data)
    {
    }

    public function deleteExpense($expense_id)
    {
    }
}
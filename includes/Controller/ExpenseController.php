<?php

namespace ExpenseTracker\Controller;

use ExpenseTracker\Modules\Expenses;
use WP_REST_Request;
use WP_REST_Response;
use ExpenseTracker\Core\Request;

class ExpenseController
{

    public function __construct() {}

    public function create_expense(WP_REST_Request $request) {}

    public function get_expenses(WP_REST_Request $request) {}
}
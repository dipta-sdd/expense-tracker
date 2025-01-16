<?php

namespace ExpenseTracker\API;

use ExpenseTracker\Core\Route;
use ExpenseTracker\Controller\ExpenseController;

class RestAPI
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        $expense_controller = new ExpenseController();

        // Expense routes
        Route::get('/expenses', [$expense_controller, 'index']);
        Route::post('/expenses', [$expense_controller, 'store']);
        Route::get('/expenses/(?P<id>\d+)', [$expense_controller, 'show']);
        Route::put('/expenses/(?P<id>\d+)', [$expense_controller, 'update']);
        Route::delete('/expenses/(?P<id>\d+)', [$expense_controller, 'destroy']);
    }
}

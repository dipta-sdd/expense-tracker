<?php

namespace ExpenseTracker\API;

use ExpenseTracker\Controller\GroupController;
use ExpenseTracker\Controller\ExpenseController;
use ExpenseTracker\Core\Route;

class RestAPI
{
    public $route;
    /**
     * Initialize the REST API
     */
    public function __construct()
    {
        add_action('rest_api_init', array($this, 'register_routes'));
    }
    /**
     * Register routes
     * 
     */
    public function register_routes()
    {
        // Group routes
        $group_controller = new GroupController();
        // Route::get('/groups', [$group_controller, 'get_groups']);
        // Route::get('/groups/(?P<id>\d+)', [$group_controller, 'get_group']);
        Route::post('/groups', [$group_controller, 'create_group']);
        Route::put('/groups/(?P<id>\d+)', [$group_controller, 'update_group']);
        Route::delete('/groups/(?P<id>\d+)', [$group_controller, 'delete_group']);

        // Expense routes
        // $expense_controller = new ExpenseController();
        // Route::get('/expenses', [$expense_controller, 'get_expenses']);
        // Route::post('/expenses', [$expense_controller, 'create_expense']);
        // Route::put('/expenses/(?P<id>\d+)', [$expense_controller, 'update_expense']);
        // Route::delete('/expenses/(?P<id>\d+)', [$expense_controller, 'delete_expense']);
    }
}
<?php

namespace ExpenseTracker\API;

use ExpenseTracker\Core\Route;
use ExpenseTracker\Controller\ExpenseController;
use ExpenseTracker\Controller\CategoryController;

class RestAPI
{
    public function __construct()
    {
        add_action('rest_api_init', [$this, 'registerRoutes']);
    }

    public function registerRoutes()
    {
        // Expense Routes
        Route::get('/expenses', [new ExpenseController(), 'index']);
        Route::post('/expenses', [new ExpenseController(), 'store']);
        Route::get('/expenses/(?P<id>\d+)', [new ExpenseController(), 'show']);
        Route::put('/expenses/(?P<id>\d+)', [new ExpenseController(), 'update']);
        Route::delete('/expenses/(?P<id>\d+)', [new ExpenseController(), 'destroy']);

        // Category Routes
        Route::get('/categories', [new CategoryController(), 'index']);
        Route::post('/categories', [new CategoryController(), 'store']);
        Route::get('/categories/(?P<id>\d+)', [new CategoryController(), 'show']);
        Route::put('/categories/(?P<id>\d+)', [new CategoryController(), 'update']);
        Route::delete('/categories/(?P<id>\d+)', [new CategoryController(), 'destroy']);
    }
}

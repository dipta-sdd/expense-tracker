<?php

namespace ExpenseTracker\API;

use ExpenseTracker\Controller\GroupController;
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

    public function register_routes()
    {
        // Route::post('users/{id}', function ($id) {});
        Route::post('/groups', array(GroupController::class, 'create_group'));
    }
}

// Initialize the REST API
new RestAPI();
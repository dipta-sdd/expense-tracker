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
    /**
     * Register routes
     * 
     */
    public function register_routes()
    {
        $group_controller = new GroupController();
        Route::post('/groups', [$group_controller, 'create_group']);
        Route::post('/group/(?P<id>\d+)', [$group_controller, 'delete_group']);
    }
}

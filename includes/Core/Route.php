<?php

namespace ExpenseTracker\Core;

class Route
{

    public function __construct()
    {
        // add_action('init', array($this, 'register_routes'));
    }

    public static function get($path, $callback)
    {
        register_rest_route('expense-tracker/v1', $path, array(
            'methods' => 'GET',
            'callback' => $callback
        ));
    }

    public static function post($path, $callback)
    {
        register_rest_route('expense-tracker/v1', $path, array(
            'methods' => 'POST',
            'callback' => $callback
        ));
    }

    public static function put($path, $callback)
    {
        register_rest_route('expense-tracker/v1', $path, array(
            'methods' => 'PUT',
            'callback' => $callback,
        ));
    }

    public static function delete($path, $callback)
    {
        register_rest_route('expense-tracker/v1', $path, array(
            'methods' => 'DELETE',
            'callback' => $callback,
        ));
    }

    public static function check()
    {
        // Register routes here
        return 'groups';
    }
}
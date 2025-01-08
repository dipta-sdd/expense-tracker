<?php

namespace ExpenseTracker\Core;

class Route
{
    private static $namespace = 'expense-tracker/v1';

    /**
     * Register a GET route
     * 
     * @param string $path The path of the route
     * @param callable $callback The callback function
     */
    public static function get($path, $callback)
    {
        self::register_route($path, 'GET', $callback);
    }

    /**
     * Register a POST route
     * 
     * @param string $path The path of the route
     * @param callable $callback The callback function
     */
    public static function post($path, $callback)
    {
        self::register_route($path, 'POST', $callback);
    }

    /**
     * Register a PUT route
     * 
     * @param string $path The path of the route
     * @param callable $callback The callback function
     */
    public static function put($path, $callback)
    {
        self::register_route($path, 'PUT', $callback);
    }

    /**
     * Register a DELETE route
     * 
     * @param string $path The path of the route
     * @param callable $callback The callback function
     */
    public static function delete($path, $callback)
    {
        self::register_route($path, 'DELETE', $callback);
    }

    /**
     * Register a route
     * 
     * @param string $path The path of the route
     * @param string $method The HTTP method of the route
     * @param callable $callback The callback function
     */
    private static function register_route($path, $method, $callback)
    {
        register_rest_route(self::$namespace, $path, array(
            'methods' => $method,
            'callback' => $callback,
            'permission_callback' => function () {
                return current_user_can('manage_options');
            }
        ));
    }
}

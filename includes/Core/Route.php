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
            'callback' => function ($wp_request) use ($callback) {
                // Convert WP_REST_Request to our custom Request
                $request = new Request($wp_request);
                return call_user_func_array($callback, [$request, ...array_values($wp_request->get_url_params())]);
            },
            'permission_callback' => '__return_true' // Permissions are handled in the controller
        ));
    }
}

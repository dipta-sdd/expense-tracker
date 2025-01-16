<?php

namespace ExpenseTracker\Core;

use NinjaDB\BaseModel;

class View
{
    private static $view_path = EXPENSE_TRACKER_PATH . 'views/';
    /**
     * Render a view
     * 
     * @param string $view The view name
     * @param array $data The data to pass to the view
     */
    public static function render($view, $data = [])
    {
        $view_file = self::get_view_file($view);

        if (!file_exists($view_file)) {
            throw new \Exception("View file not found: {$view_file}");
        }

        extract($data);
        include $view_file;
    }
    /**
     * Get the view file
     * 
     * @param string $view The view name
     * @return string The view file path
     */
    private static function get_view_file($view)
    {
        if (is_array($view)) {
            return self::$view_path . rtrim($view[0], '.php') . '.php';
        }
        return self::$view_path . rtrim($view, '.php') . '.php';
    }

    /**
     * Render the expenses page
     */
    public static function admin_expenses_page()
    {
        $data = [
            'expenses' => [], // Get from ExpenseController
        ];
        self::render('admin/expenses_page', $data);
    }
    /**
     * Render the dashboard page
     */
    public static function admin_dashboard_page()
    {
        self::render('admin/dashboard_page');
    }

    /**
     * Render the settings page
     */
    public static function admin_settings_page()
    {
        self::render('admin/settings_page');
    }
}

<?php

namespace ExpenseTracker\Core;

class View
{
    /**
     * Render a view file with optional data
     * 
     * @param string $view The view file name without .php extension
     * @param array $data Data to be passed to the view
     * @return void
     */
    public static function render($view, $data = [])
    {
        if (is_array($view)) {
            // If first parameter is array, extract view name and data
            $viewFile = $view[0];
            $data = isset($view[1]) ? $view[1] : [];
        } else {
            $viewFile = $view;
        }

        // Ensure .php extension is added
        $viewFile = rtrim($viewFile, '.php') . '.php';

        // Extract data to make variables available in view
        extract($data);

        // Include the view file
        $viewPath = EXPENSE_TRACKER_PATH . 'views/' . $viewFile;
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            throw new \Exception("View file not found: {$viewPath}");
        }
    }

    public static function admin_expenses_page()
    {
        $data = [
            'expenses' => 'data',
        ];
        self::render('admin/expenses_page', $data);
    }

    public static function admin_dashboard_page()
    {
        self::render('admin/dashboard_page');
    }

    public static function admin_groups_page()
    {
        $action = isset($_GET['action']) ? sanitize_text_field($_GET['action']) : '';

        if ($action === 'new' || $action === 'edit') {
            self::render('admin/groups_form');
        } else {
            self::render('admin/groups_page');
        }
    }

    public static function admin_settings_page()
    {
        self::render('admin/settings_page');
    }
}

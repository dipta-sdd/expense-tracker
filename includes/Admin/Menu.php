<?php

namespace ExpenseTracker\Admin;

class Menu
{
    public static function register_menus()
    {
        add_menu_page(
            __('Expense Tracker', 'expense-tracker'), // Page title
            __('Expense Tracker', 'expense-tracker'), // Menu title
            'manage_options',                        // Capability
            'expense-tracker',                      // Menu slug
            array('ExpenseTracker\Admin\Settings', 'display_settings_page'), // Callback function
            'dashicons-money',                      // Icon (optional)
            20                                       // Position (optional)
        );

        add_submenu_page(
            'expense-tracker',                     // Parent menu slug
            __('Add Expense', 'expense-tracker'),  // Page title
            __('Add Expense', 'expense-tracker'),  // Menu title
            'manage_options',                       // Capability
            'expense-tracker-add',                 // Menu slug
            array(__CLASS__, 'display_add_expense_page') // Callback function
        );
    }
    public static function display_add_expense_page()
    {
        // Render the "Add Expense" page content
        echo '<div class="wrap">';
        echo '<h1>' . __('Add New Expense', 'expense-tracker') . '</h1>';
        echo '<p>' . __('This is the page where users will add new expenses.', 'expense-tracker') . '</p>';
        echo '</div>';
    }
}
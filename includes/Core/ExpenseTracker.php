<?php

namespace ExpenseTracker\Core;

use ExpenseTracker\Admin\Settings;

class ExpenseTracker
{

    public function __construct() {}
    public function init()
    {
        $this->activate_admin();

        // add_action('wp_enqueue_scripts', array($this, 'enqueue_public_assets'));
    }


    public function after_activation_hook()
    {
        // Check if the plugin has just been activated
        if (get_option('expense_tracker_activated')) {
            delete_option('expense_tracker_activated');
        }
    }
    public function activate_admin()
    {
        $admin_settings = new \ExpenseTracker\Admin\Settings();
        $admin_settings->add_assets();

        // Add admin menu
        add_action('admin_menu', array($admin_settings, 'register_admin_menu'));

        // Add admin init
        add_action('admin_init', array($admin_settings, 'expense_tracker_settings_init'));
    }



    /**
     * Enqueue public-facing styles and scripts
     */
    public function enqueue_public_assets()
    {
        wp_enqueue_style(
            'expense-tracker-public',
            EXPENSE_TRACKER_URL . 'assets/css/public.css',
            array(),
            EXPENSE_TRACKER_VERSION
        );

        wp_enqueue_script(
            'expense-tracker-public',
            EXPENSE_TRACKER_URL . 'assets/js/public.js',
            array('jquery'),
            EXPENSE_TRACKER_VERSION,
            true
        );
    }
}

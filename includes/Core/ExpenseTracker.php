<?php

namespace ExpenseTracker\Core;

use ExpenseTracker\Admin\Settings;
use ExpenseTracker\API\RestAPI;

class ExpenseTracker
{
    private $settings;
    private $rest_api;
    private $view;
    private static $instance = false;
    private $shortcode;
    public function __construct()
    {
        if (!self::$instance) {
            self::$instance = $this;
        }
        $this->init_dependencies();
        $this->init_hooks();
        $this->init();
        return self::$instance;
    }

    private function init_dependencies()
    {
        // Initialize dependencies
        // Initialize settings
        $this->settings = new Settings($this);
        // Initialize rest api
        $this->rest_api = new RestAPI();
        // Initialize view
        $this->view = new View();
        // Initialize shortcode
        $this->shortcode = new Shortcode();
    }

    private function init_hooks()
    {
        // Initialize hooks
        add_action('init', [$this, 'init']);
        add_action('admin_init', [$this->settings, 'expense_tracker_settings_init']);
        add_action('admin_menu', [$this->settings, 'register_admin_menu']);
    }

    public function init()
    {
        $this->enqueue_assets();
    }

    public function activate()
    {
        if (!get_option('expense_tracker_activated')) {
            add_option('expense_tracker_activated', true);
        }
    }



    private function enqueue_assets()
    {
        add_action('wp_enqueue_scripts', [$this, 'enqueue_public_assets']);
        add_action('admin_enqueue_scripts', [$this->settings, 'enqueue_admin_assets']);
    }
    /**
     * Add a script to the WordPress enqueue queue.
     *
     * @param string $handle The handle of the script.
     * @param string $src The source URL of the script.
     */
    public function add_script($handle, $src)
    {
        wp_enqueue_script(
            $handle,
            EXPENSE_TRACKER_URL . $src,
            array('jquery'),
            EXPENSE_TRACKER_VERSION,
            true
        );
    }

    /**
     * Add a style to the WordPress enqueue queue.
     *
     * @param string $handle The handle of the style.
     * @param string $src The source URL of the style.
     */
    public function add_style($handle, $src)
    {
        wp_enqueue_style(
            $handle,
            EXPENSE_TRACKER_URL . $src,
            array(),
            EXPENSE_TRACKER_VERSION
        );
    }

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

    public function enqueue_scripts()
    {
        // Enqueue existing styles...

        // Enqueue the JavaScript file
        wp_enqueue_script(
            'expense-tracker-public',
            plugins_url('assets/js/public.js', EXPENSE_TRACKER_FILE),
            array('jquery'),
            EXPENSE_TRACKER_VERSION,
            true
        );
    }
}

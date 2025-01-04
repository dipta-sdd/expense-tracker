<?php

namespace ExpenseTracker\Core;

class ExpenseManager
{

    public function init()
    {
        // Add actions for admin menus
        add_action('admin_menu', array('\ExpenseTracker\Admin\Menu', 'register_menus'));

        // // Add actions for settings pages
        // add_action('admin_init', array('\ExpenseTracker\Admin\Settings', 'register_settings'));

        // // Enqueue scripts and styles
        // add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
        // add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts'));
    }
}
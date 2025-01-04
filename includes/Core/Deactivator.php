<?php

class Deactivator
{

    public static function deactivate()
    {
        global $wpdb;

        // Drop database tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}expenses");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}categories");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}groups");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}group_members");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}budgets");

        // Delete plugin options (if any)
        // delete_option( 'expense_tracker_option_name' ); 
    }
}
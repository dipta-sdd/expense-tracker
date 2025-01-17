<?php

namespace ExpenseTracker\Database;

class Migration
{
    public static function make_migration()
    {
        global $wpdb;
        $plugin_prefix = 'expense_tracker_';
        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        // Create categories table
        $table_name = $wpdb->prefix . $plugin_prefix . 'categories';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL,
            budget DECIMAL(10,2) Default NULL,
            created_by BIGINT UNSIGNED DEFAULT NULL, 
            updated_by BIGINT UNSIGNED DEFAULT NULL, 
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES {$wpdb->prefix}users(`ID`)");

        // Create expenses table
        $table_name = $wpdb->prefix . $plugin_prefix . 'expenses';
        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            category_id BIGINT UNSIGNED NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            date DATE NOT NULL,
            description TEXT DEFAULT NULL,
            status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
            created_by BIGINT UNSIGNED DEFAULT NULL,
            updated_by BIGINT UNSIGNED DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`created_by`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`updated_by`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}categories(`id`)");

        update_option('expense_tracker_activated', 1);
    }
    public function run()
    {
        $this->make_migration();
    }
    public static function deactivate()
    {
        global $wpdb;
        $plugin_prefix = 'expense_tracker_';
        // Drop database tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}expenses");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}categories");
    }
}

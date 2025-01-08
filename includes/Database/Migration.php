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



        // Create groups table
        $table_name = $wpdb->prefix . $plugin_prefix . 'groups';
        $sql = "CREATE TABLE $table_name (
            group_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            budget DECIMAL(10,2) DEFAULT NULL,
            description TEXT,
            admin_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`admin_id`) REFERENCES {$wpdb->prefix}users(`ID`)");


        // Create categories table
        $table_name = $wpdb->prefix . $plugin_prefix . 'categories';
        $sql = "CREATE TABLE $table_name (
            category_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            user_id BIGINT UNSIGNED DEFAULT NULL, 
            group_id BIGINT UNSIGNED DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");

        // Create expenses table
        $table_name = $wpdb->prefix . $plugin_prefix . 'expenses';
        $sql = "CREATE TABLE $table_name (
            expense_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            category_id BIGINT UNSIGNED NOT NULL,
            group_id BIGINT UNSIGNED DEFAULT NULL, 
            amount DECIMAL(10,2) NOT NULL,
            date DATE NOT NULL,
            description TEXT,
            status VARCHAR(20) DEFAULT 'Active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}categories(`category_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}groups(`group_id`)");

        // Create group_members table
        $table_name = $wpdb->prefix . $plugin_prefix . 'group_members';
        $sql = "CREATE TABLE $table_name (
            group_id BIGINT UNSIGNED NOT NULL,
            user_id BIGINT UNSIGNED NOT NULL,
            role VARCHAR(50) NOT NULL,
            status VARCHAR(20) NOT NULL,
            added_by BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            PRIMARY KEY (group_id, user_id)
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`added_by`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}groups(`group_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");


        // Create budgets table
        $table_name = $wpdb->prefix . $plugin_prefix . 'budgets';
        $sql = "CREATE TABLE $table_name (
            budget_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED DEFAULT NULL,
            group_id BIGINT UNSIGNED DEFAULT NULL,
            category_id BIGINT UNSIGNED DEFAULT NULL,
            type VARCHAR(10) NOT NULL,
            amount DECIMAL(10,2) NOT NULL,
            start_date DATE DEFAULT NULL,
            end_date DATE DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}groups(`group_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}{$plugin_prefix}categories(`category_id`)");

        update_option('expense_tracker_activated', 1);
    }
    public static function deactivate()
    {
        global $wpdb;
        $plugin_prefix = 'expense_tracker_';
        // Drop database tables
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}budgets");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}expenses");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}group_members");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}groups");
        $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$plugin_prefix}categories");
    }
}

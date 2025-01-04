<?php

namespace ExpenseTracker\Database;

class Migration
{

    public static function migrate()
    {
        global $wpdb;

        $charset_collate = $wpdb->get_charset_collate();
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');



        // Create groups table
        $table_name = $wpdb->prefix . 'groups';
        $sql = "CREATE TABLE $table_name (
            group_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            admin_id BIGINT UNSIGNED NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`admin_id`) REFERENCES {$wpdb->prefix}users(`ID`)");


        // Create categories table
        $table_name = $wpdb->prefix . 'categories';
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
        $table_name = $wpdb->prefix . 'expenses';
        $sql = "CREATE TABLE $table_name (
            expense_id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
            user_id BIGINT UNSIGNED NOT NULL,
            category_id BIGINT UNSIGNED NOT NULL,
            group_id BIGINT UNSIGNED DEFAULT NULL, 
            amount DECIMAL(10,2) NOT NULL,
            date DATE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) $charset_collate;";
        dbDelta($sql);
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}categories(`category_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}groups(`group_id`)");

        // Create group_members table
        $table_name = $wpdb->prefix . 'group_members';
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
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}groups(`group_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`user_id`) REFERENCES {$wpdb->prefix}users(`ID`)");


        // Create budgets table
        $table_name = $wpdb->prefix . 'budgets';
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
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`group_id`) REFERENCES {$wpdb->prefix}groups(`group_id`)");
        $wpdb->query("ALTER TABLE $table_name ADD CONSTRAINT FOREIGN KEY (`category_id`) REFERENCES {$wpdb->prefix}categories(`category_id`)");
    }
}


// WordPress database error: [You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FOREIGN KEY (admin_id) REFERENCES wp_users(ID)' at line 1]
// ALTER TABLE wp_groups ADD COLUMN FOREIGN KEY (admin_id) REFERENCES wp_users(ID)

// WordPress database error: [You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FOREIGN KEY (user_id) REFERENCES wp_users(ID)' at line 1]
// ALTER TABLE wp_categories ADD COLUMN FOREIGN KEY (user_id) REFERENCES wp_users(ID)

// WordPress database error: [You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FOREIGN KEY (added_by) REFERENCES wp_users(ID)' at line 1]
// ALTER TABLE wp_group_members ADD COLUMN FOREIGN KEY (added_by) REFERENCES wp_users(ID)

// WordPress database error: [You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'FOREIGN KEY (category_id) REFERENCES wp_categories(category_id)' at line 1]
// ALTER TABLE wp_budgets ADD COLUMN FOREIGN KEY (category_id) REFERENCES wp_categories(category_id)
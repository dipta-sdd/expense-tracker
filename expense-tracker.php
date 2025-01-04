<?php

/**
 * Plugin Name: Expense Tracker
 * Plugin URI:  (Your Plugin Website URL)
 * Description: A WordPress plugin for tracking personal and group expenses.
 * Version:     1.0.0
 * Author:      (Sankarsan Das)
 * Author URI:  (www.sankarsan.xyz)
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: expense-tracker
 * Domain Path: /languages
 */

// If this file is called directly, abort.
if (! defined('WPINC')) {
    die;
}

// Define plugin constants
define('EXPENSE_TRACKER_VERSION', '1.0.0');
define('EXPENSE_TRACKER_PATH', plugin_dir_path(__FILE__));
define('EXPENSE_TRACKER_URL', plugin_dir_url(__FILE__));


// Include the App class
require_once EXPENSE_TRACKER_PATH . 'includes/App/App.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Activator.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Deactivator.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Database/Migration.php'; // Include Database.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Expense.php'; // Include Expense.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Category.php'; // Include Category.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Group.php'; // Include Group.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/GroupMember.php'; // Include GroupMember.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Budget.php'; // Include Budget.php
require_once EXPENSE_TRACKER_PATH . 'includes/Core/ExpenseManager.php'; // Include ExpenseManager.php
require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Menu.php'; // Include Menu.php
require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Settings.php'; // Include Settings.php



// Create and run the app
$app = new \ExpenseTracker\App\App();
$app->run();
register_activation_hook(__FILE__, array('\ExpenseTracker\Database\Migration', 'migrate'));
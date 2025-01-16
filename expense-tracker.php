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

// Define plugin constants if not already defined
if (!defined('EXPENSE_TRACKER_PATH')) {
    define('EXPENSE_TRACKER_PATH', plugin_dir_path(__FILE__));
}


define('EXPENSE_TRACKER_URL', plugin_dir_url(__FILE__));
// Include NinjaDB autoloader first
require_once EXPENSE_TRACKER_PATH . 'vendor/NinjaDB/autoload.php';

// // Then include other files
require_once EXPENSE_TRACKER_PATH . 'includes/Core/ExpenseTracker.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Database/Migration.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Expenses.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Categories.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Settings.php';
require_once EXPENSE_TRACKER_PATH . 'includes/API/RestAPI.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Route.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Request.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/View.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Controller/ExpenseController.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Controller/CategoryController.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Controller/ReportHandler.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/FormHandler.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Shortcode.php';

if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants if not already defined
define('EXPENSE_TRACKER_VERSION', '1.0.0');
define('EXPENSE_TRACKER_FILE', __FILE__);

// Initialize the plugin
function expense_tracker_init()
{
    return ExpenseTracker\Core\ExpenseTracker::getInstance();
}

// Start the plugin
expense_tracker_init();

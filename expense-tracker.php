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
define('EXPENSE_TRACKER_BASENAME', plugin_basename(__FILE__));
define('EXPENSE_TRACKER_UPLOADS', wp_upload_dir()['basedir'] . '/expense-tracker');
define('TEXT_DOMAIN', 'expense-tracker');

// Include the App class
require_once EXPENSE_TRACKER_PATH . 'includes/Core/ExpenseTracker.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Database/Migration.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Helpers/utils.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Expenses.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Groups.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Categories.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/Budgets.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Modules/GroupMembers.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Admin/Settings.php';
require_once EXPENSE_TRACKER_PATH . 'includes/API/RestAPI.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/Route.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Core/View.php';
require_once EXPENSE_TRACKER_PATH . 'includes/Controller/GroupController.php';
// require_once EXPENSE_TRACKER_PATH . 'includes/Notifications/EmailNotifications.php';
// require_once EXPENSE_TRACKER_PATH . 'includes/Reports/ReportGenerator.php';

// Add activation hooks
register_activation_hook(EXPENSE_TRACKER_BASENAME, function () {
    // Create upload directory for receipts
    wp_mkdir_p(EXPENSE_TRACKER_UPLOADS);

    // Run migrations
    ExpenseTracker\Database\Migration::make_migration();
});

// Add deactivation hook
register_deactivation_hook(EXPENSE_TRACKER_BASENAME, array(ExpenseTracker\Database\Migration::class, 'deactivate'));

// Initialize the plugin
$expense_tracker = new ExpenseTracker\Core\ExpenseTracker();
$expense_tracker->init();

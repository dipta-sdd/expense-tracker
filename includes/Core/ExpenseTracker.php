<?php

namespace ExpenseTracker\Core;

class ExpenseTracker
{
    private static $instance = null;
    private $modules = [];

    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    private function __construct()
    {
        $this->initHooks();
        $this->loadModules();
    }

    private function initHooks()
    {
        register_activation_hook(EXPENSE_TRACKER_FILE, [$this, 'activate']);
        register_deactivation_hook(EXPENSE_TRACKER_FILE, [$this, 'deactivate']);
        add_action('init', [$this, 'init']);
    }

    private function loadModules()
    {
        // Initialize core modules
        $this->modules['expenses'] = new \ExpenseTracker\Modules\Expenses();
        $this->modules['categories'] = new \ExpenseTracker\Modules\Categories();
        $this->modules['settings'] = new \ExpenseTracker\Admin\Settings();
        $this->modules['api'] = new \ExpenseTracker\API\RestAPI();
    }

    public function activate()
    {
        // Run migrations
        $migration = new \ExpenseTracker\Database\Migration();
        $migration->run();

        // Set initial role capabilities
        $this->setupInitialRoleCapabilities();
    }

    private function setupInitialRoleCapabilities()
    {
        $initial_caps = [
            'administrator' => [
                'manage_expenses',
                'submit_expenses',
                'view_expenses',
                'approve_expenses',
                'view_reports'
            ],
            'editor' => [
                'submit_expenses',
                'view_expenses',
                'approve_expenses'
            ],
            'author' => [
                'submit_expenses',
                'view_expenses'
            ]
        ];

        update_option('expense_tracker_role_capabilities', $initial_caps);

        // Apply the capabilities
        $settings = new \ExpenseTracker\Admin\Settings();
        $settings->updateRoleCapabilities();
    }

    public function deactivate()
    {
        // Cleanup if needed
    }

    public function init()
    {
        // Initialize shortcodes
        new \ExpenseTracker\Core\Shortcode();
    }

    private function setupRoles()
    {
        // Add capabilities to Administrator
        $admin = get_role('administrator');
        $admin->add_cap('manage_expenses');
        $admin->add_cap('submit_expenses');
        $admin->add_cap('view_expenses');
        $admin->add_cap('approve_expenses');
        $admin->add_cap('view_reports');

        // Add capabilities to Editor
        $editor = get_role('editor');
        $editor->add_cap('submit_expenses');
        $editor->add_cap('view_expenses');
        $editor->add_cap('approve_expenses');

        // Add capabilities to Author
        $author = get_role('author');
        $author->add_cap('submit_expenses');
        $author->add_cap('view_expenses');
    }

    public function getModule($module)
    {
        return isset($this->modules[$module]) ? $this->modules[$module] : null;
    }
}

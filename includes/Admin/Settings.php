<?php

namespace ExpenseTracker\Admin;

class Settings
{
    private $menu_slug = 'expense-tracker';

    public function __construct()
    {
        add_action('admin_init', [$this, 'registerSettings']);
        add_action('admin_enqueue_scripts', [$this, 'enqueueScripts']);
        add_action('admin_menu', [$this, 'registerAdminMenus']);
    }
    public function registerAdminMenus()
    {
        // Main menu
        add_menu_page(
            __('Expense Tracker', 'expense-tracker'),
            __('Expense Tracker', 'expense-tracker'),
            'manage_options',
            $this->menu_slug,
            [$this, 'renderMainPage'],
            'dashicons-money-alt',
            30
        );

        // Submenu pages
        add_submenu_page(
            $this->menu_slug,
            __('All Expenses', 'expense-tracker'),
            __('All Expenses', 'expense-tracker'),
            'manage_options',
            $this->menu_slug,
            [$this, 'renderMainPage']
        );

        add_submenu_page(
            $this->menu_slug,
            __('Add New Expense', 'expense-tracker'),
            __('Add New', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-add-new',
            [$this, 'renderAddNewPage']
        );

        // Additional submenu pages
        add_submenu_page(
            $this->menu_slug,
            __('Categories', 'expense-tracker'),
            __('Categories', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-categories',
            [$this, 'renderCategoriesPage']
        );

        add_submenu_page(
            $this->menu_slug,
            __('Reports', 'expense-tracker'),
            __('Reports', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-reports',
            [$this, 'renderReportsPage']
        );
        add_submenu_page(
            $this->menu_slug,
            __('Settings', 'expense-tracker'),
            __('Settings', 'expense-tracker'),
            'manage_options',
            $this->menu_slug . '-settings',
            [$this, 'renderSettingsPage']
        );
    }


    public function registerSettings()
    {
        register_setting('expense_tracker_roles', 'expense_tracker_role_capabilities');

        add_settings_section(
            'role_capabilities',
            __('Role Capabilities', 'expense-tracker'),
            [$this, 'renderCapabilitiesSection'],
            $this->menu_slug
        );
    }

    public function renderSettingsPage()
    {
        $roles = $this->getRoles();
        $capabilities = $this->getExpenseCapabilities();
        $current_settings = get_option('expense_tracker_role_capabilities', []);
?>
        <div class="wrap">
            <h1><?php echo esc_html__('Expense Tracker Settings', 'expense-tracker'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('expense_tracker_roles'); ?>
                <table class="form-table" role="presentation">
                    <thead>
                        <tr>
                            <th scope="col"><?php esc_html_e('Capabilities', 'expense-tracker'); ?></th>
                            <?php foreach ($roles as $role_id => $role) : ?>
                                <th scope="col"><?php echo esc_html($role['name']); ?></th>
                            <?php endforeach; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($capabilities as $cap => $label) : ?>
                            <tr>
                                <th scope="row"><?php echo esc_html($label); ?></th>
                                <?php foreach ($roles as $role_id => $role) : ?>
                                    <td>
                                        <input type="checkbox"
                                            name="expense_tracker_role_capabilities[<?php echo esc_attr($role_id); ?>][]"
                                            value="<?php echo esc_attr($cap); ?>"
                                            <?php checked(isset($current_settings[$role_id]) && in_array($cap, $current_settings[$role_id])); ?>
                                            <?php disabled($role_id === 'administrator'); ?>>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php submit_button(); ?>
            </form>
        </div>
<?php
    }

    private function getRoles()
    {
        $wp_roles = wp_roles();
        return $wp_roles->roles;
    }

    private function getExpenseCapabilities()
    {
        return [
            'manage_expenses' => __('Manage All Expenses', 'expense-tracker'),
            'submit_expenses' => __('Submit Expenses', 'expense-tracker'),
            'view_expenses' => __('View Expenses', 'expense-tracker'),
            'approve_expenses' => __('Approve/Reject Expenses', 'expense-tracker'),
            'view_reports' => __('View Reports', 'expense-tracker')
        ];
    }

    public function updateRoleCapabilities()
    {
        $settings = get_option('expense_tracker_role_capabilities', []);

        foreach ($settings as $role_id => $capabilities) {
            $role = get_role($role_id);
            if (!$role) continue;

            foreach ($this->getExpenseCapabilities() as $cap => $label) {
                $role->remove_cap($cap);
            }

            foreach ($capabilities as $cap) {
                $role->add_cap($cap);
            }
        }
    }

    public function enqueueScripts($hook)
    {
        // error_log($hook);
        if (strpos($hook, 'expense-tracker_page_expense-tracker-reports') === false) {
            return;
        }
        wp_enqueue_script('chart-js', EXPENSE_TRACKER_URL . 'assets/js/chart.js', [], '3.7.0', true);
        // wp_enqueue_script(
        //     'expense-tracker-reports',
        //     EXPENSE_TRACKER_URL . 'assets/js/admin/reports.js',
        //     ['jquery', 'chart-js'],
        //     EXPENSE_TRACKER_VERSION,
        //     true
        // );
        // name of the script, data to pass to the script
        // wp_localize_script('expense-tracker-reports', 'expense_tracker', [
        //     'ajax_url' => admin_url('admin-ajax.php'),
        //     'nonce' => wp_create_nonce('expense_tracker_nonce')
        // ]);
    }
    public function renderMainPage()
    {
        // $view = new \ExpenseTracker\Core\View();
        expense_tracker_init()->getModule('view')->render('admin/expenses/list');
    }

    public function renderAddNewPage()
    {
        // $view = new \ExpenseTracker\Core\View();
        if (isset($_GET['id'])) {
            expense_tracker_init()->getModule('view')->render('admin/expenses/edit');
        } else {
            expense_tracker_init()->getModule('view')->render('admin/expenses/new');
        }
    }

    public function renderCategoriesPage()
    {
        // $view = new \ExpenseTracker\Core\View();
        expense_tracker_init()->getModule('view')->render('admin/categories/list');
    }

    public function renderReportsPage()
    {
        expense_tracker_init()->getModule('view')->render('admin/reports/index');
    }
}

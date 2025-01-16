<?php

namespace ExpenseTracker\Admin;

class Settings
{
    private $menu_slug = 'expense-tracker-settings';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerSettingsPage']);
        add_action('admin_init', [$this, 'registerSettings']);
    }

    public function registerSettingsPage()
    {
        add_submenu_page(
            'expense-tracker',
            __('Settings', 'expense-tracker'),
            __('Settings', 'expense-tracker'),
            'manage_options',
            $this->menu_slug,
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

            // Remove all expense tracker capabilities first
            foreach ($this->getExpenseCapabilities() as $cap => $label) {
                $role->remove_cap($cap);
            }

            // Add selected capabilities
            foreach ($capabilities as $cap) {
                $role->add_cap($cap);
            }
        }
    }
}

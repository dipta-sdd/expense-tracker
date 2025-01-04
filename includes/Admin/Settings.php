<?php

namespace ExpenseTracker\Admin;

class Settings
{
    public static function register_settings()
    {
        register_setting(
            'expense_tracker_settings_group',  // Settings group name
            'expense_tracker_default_category' // Setting name
        );
        add_settings_section(
            'expense_tracker_general_section',      // Section ID
            __('General Settings', 'expense-tracker'), // Section title
            array(__CLASS__, 'general_section_callback'), // Callback function
            'expense-tracker'                            // Settings page slug
        );
    }

    public static function general_section_callback()
    {
        // Output the description for the general settings section
        echo '<p>' . __('These settings control the general behavior of the Expense Tracker plugin.', 'expense-tracker') . '</p>';
    }

    public static function default_category_callback()
    {
        // Output the input field for the default category setting
        $setting = get_option('expense_tracker_default_category');
        echo '<input type="text" name="expense_tracker_default_category" value="' . esc_attr($setting) . '" />';
    }

    public static function display_settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php echo __('Expense Tracker Settings', 'expense-tracker'); ?></h1>
            <form method="post" action="options.php">
                <?php settings_fields('expense_tracker_settings_group'); ?>
                <?php do_settings_sections('expense-tracker'); ?>
                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}

?>
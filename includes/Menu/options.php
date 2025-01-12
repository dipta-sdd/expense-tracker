<div class="wrap">
    <h1><?php echo __('Expense Tracker Settings', 'expense-tracker'); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields('expense_tracker_settings_group');
        do_settings_sections('expense-tracker-settings');
        submit_button();
        ?>
    </form>
</div>
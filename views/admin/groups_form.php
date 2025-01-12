<?php
if (!defined('ABSPATH')) {
    exit;
}


$is_edit = isset($group);
$title = $is_edit ? __('Edit Group', 'expense-tracker') : __('Add New Group', 'expense-tracker');
?>

<div class="wrap expense-tracker-wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?></h1>
    <hr class="wp-header-end">

    <div class="et-card et-bg-light">
        <form method="post" id="group-form">

            <?php wp_nonce_field('et_group_nonce'); ?>
            <?php if ($is_edit): ?>
            <input type="hidden" name="group_id" value="<?php echo esc_attr($group->group_id); ?>">
            <?php endif; ?>
            <div class="et-grid et-grid-2">
                <div class="et-form-group">
                    <label for="group_name"
                        class="et-text-tertiary"><?php echo esc_html__('Group Name', 'expense-tracker'); ?></label>
                    <input type="text" id="group_name" name="name" class="regular-text et-input"
                        value="<?php echo isset($group) ? esc_attr($group->name) : ''; ?>" required>
                    <small class="text-danger"></small>
                </div>

                <div class="et-form-group">
                    <label for="group_budget"
                        class="et-text-tertiary"><?php echo esc_html__('Budget', 'expense-tracker'); ?></label>
                    <input type="number" id="group_budget" name="budget" class="regular-text et-input" step="0.01"
                        min="0" value="<?php echo isset($group) ? esc_attr($group->budget) : ''; ?>" required>
                    <small class="text-danger"></small>
                </div>
            </div>
            <div class="et-form-group">
                <label for="group_description"
                    class="et-text-tertiary"><?php echo esc_html__('Description', 'expense-tracker'); ?></label>
                <textarea id="group_description" name="description" class="regular-text et-input"
                    rows="4"><?php echo isset($group) ? esc_attr($group->description) : ''; ?></textarea>
                <small class="text-danger"></small>
            </div>

            <div class="et-actions">
                <button type="button" class="button button-primary submit">
                    <span class="dashicons dashicons-saved"></span>
                    <?php echo esc_html__('Save Group', 'expense-tracker'); ?>
                </button>
                <a href="?page=expense-tracker-groups" class="button button-secondary">
                    <span class="dashicons dashicons-dismiss"></span>
                    <?php echo esc_html__('Cancel', 'expense-tracker'); ?>
                </a>
            </div>
        </form>
    </div>
</div>

<?php
wp_enqueue_script('expense-tracker-groups-form', EXPENSE_TRACKER_URL . 'assets/js/groups_form.js', array('jquery'), '', true);
wp_localize_script('expense-tracker-groups-form', 'wpApiSettings', array(
    'nonce' => wp_create_nonce('wp_rest'),
));
?>
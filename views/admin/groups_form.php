<?php
if (!defined('ABSPATH')) {
    exit;
}

$group_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$is_edit = $group_id > 0;
$title = $is_edit ? __('Edit Group', 'expense-tracker') : __('Add New Group', 'expense-tracker');
?>

<div class="wrap expense-tracker-wrap">
    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?></h1>
    <hr class="wp-header-end">

    <div class="et-card et-bg-light">
        <form method="post" id="group-form">
            <input type="hidden" name="group_id" value="<?php echo esc_attr($group_id); ?>">
            <?php wp_nonce_field('et_group_nonce'); ?>
            <div class="et-grid et-grid-2">
                <div class="et-form-group">
                    <label for="group_name"
                        class="et-text-tertiary"><?php echo esc_html__('Group Name', 'expense-tracker'); ?></label>
                    <input type="text" id="group_name" name="group_name" class="regular-text" required>
                </div>

                <div class="et-form-group">
                    <label for="group_budget"
                        class="et-text-tertiary"><?php echo esc_html__('Budget', 'expense-tracker'); ?></label>
                    <input type="number" id="group_budget" name="group_budget" class="regular-text" step="0.01" min="0"
                        required>
                </div>
            </div>
            <div class="et-form-group">
                <label for="group_description"
                    class="et-text-tertiary"><?php echo esc_html__('Description', 'expense-tracker'); ?></label>
                <textarea id="group_description" name="group_description" class="regular-text" rows="4"></textarea>
            </div>

            <div class="et-actions">
                <button type="submit" class="button button-primary">
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

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $('#group-form').on('submit', function(e) {
            e.preventDefault();

            const formData = {
                name: $('#group_name').val(),
                budget: $('#group_budget').val(),
                description: $('#group_description').val()
            };

            $.ajax({
                url: '<?php echo esc_url_raw(rest_url('expense-tracker/v1/groups')); ?>',
                method: 'POST',
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce',
                        '<?php echo wp_create_nonce('wp_rest'); ?>');
                },
                data: JSON.stringify(formData),
                contentType: 'application/json',
                success: function(response) {
                    // window.location.href = '?page=expense-tracker-groups';
                    console.log(response);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Error saving group');
                }
            });
        });
    });
</script>
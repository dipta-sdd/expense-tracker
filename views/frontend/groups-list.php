<?php if (!defined('ABSPATH')) exit; ?>
<!-- <?php echo json_encode($groups); ?> -->

<div class="et-flex et-flex-row" style="width: 100%; gap: 10px;">
    <input type="text" id="group_name" name="name" class="regular-text et-input et-flex-grow"
        value="<?php echo isset($group) ? esc_attr($group->name) : ''; ?>">
    <button class="et-button et-text-primary">Search</button>
    <?php if ($permission_check) : ?>
    <a href="<?php echo esc_url(add_query_arg('action', 'add', \ExpenseTracker\Core\Pages::get_page_url('groups'))); ?>"
        class="et-button et-bg-primary et-text-light">Add
        New Group</a>
    <?php endif; ?>
</div>
<?php if (empty($groups)): ?>
<p><?php esc_html_e('No groups found.', 'expense-tracker'); ?></p>
<?php else: ?>
<div class="et-grid">
    <?php foreach ($groups as $group): ?>
    <div class="et-flex et-flex-column et-border et-p-3 et-rounded" style="align-items: stretch;">
        <div class="et-flex et-flex-row et-flex-grow et-flex-space-between">
            <h3 class="et-group-name">
                <a class="et-a et-text-primary"
                    href="<?php echo esc_url(add_query_arg('group_id', $group->group_id, \ExpenseTracker\Core\Pages::get_page_url('single_group'))); ?>">
                    <?php echo esc_html($group->name); ?>
                </a>
            </h3>
            <span class="et-badge et-bg-primary et-text-light">
                <?php echo esc_html(ucfirst($group->user_role)); ?>
            </span>
        </div>
        <small class="et-group-description">
            <?php echo wp_trim_words($group->description, 15); ?>
        </small>
        <div class="et-flex et-flex-row et-flex-grow et-flex-space-between">
            <div class="et-flex et-flex-center">
                <span class="dashicons dashicons-admin-users"></span>
                <?php echo sprintf(
                            esc_html__('%d members', 'expense-tracker'),
                            esc_html($group->member_count)
                        ); ?>
            </div>
            <div class="et-flex et-flex-center">
                <span class="dashicons dashicons-businessman"></span>
                <?php echo esc_html($group->admin_name); ?>
            </div>

        </div>


    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
<?php include_once 'pagination.php'; ?>
<script>
jQuery(document).ready(function($) {
    $('.et-pagination-controls .et-per-page select').on('change', function(e) {
        e.preventDefault();
        const limit = $(this).val();
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('limit')) {
            urlParams.set('limit', limit);
        } else {
            urlParams.append('limit', limit);
        }
        urlParams.delete('offset');
        window.location.href = '?' + urlParams.toString();
    });

    $('.et-pagination-controls .et-pagination-buttons a').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('offset')) {
            urlParams.set('offset', page);
        } else {
            urlParams.append('offset', page);
        }
        window.location.href = '?' + urlParams.toString();
    });
});
</script>
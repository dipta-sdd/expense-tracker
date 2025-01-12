<?php if (!defined('ABSPATH')) exit; ?>

<div class="et-groups-grid">
    <?php if (empty($groups)): ?>
    <p><?php esc_html_e('No groups found.', 'expense-tracker'); ?></p>
    <?php else: ?>
    <div class="et-grid-container">
        <?php foreach ($groups as $group): ?>
        <div class="et-grid-item">
            <div class="et-grid-header">
                <span class="et-group-role <?php echo esc_attr($group->user_role); ?>">
                    <?php echo esc_html(ucfirst($group->user_role)); ?>
                </span>
                <h3 class="et-group-name">
                    <a
                        href="<?php echo esc_url(add_query_arg('group_id', $group->group_id, \ExpenseTracker\Core\Pages::get_page_url('single_group'))); ?>">
                        <?php echo esc_html($group->name); ?>
                    </a>
                </h3>
            </div>
            <div class="et-grid-body">
                <p class="et-group-description">
                    <?php echo wp_trim_words($group->description, 15); ?>
                </p>
            </div>
            <div class="et-grid-footer">
                <div class="et-footer-item">
                    <span class="dashicons dashicons-admin-users"></span>
                    <?php echo sprintf(
                                esc_html__('%d members', 'expense-tracker'),
                                esc_html($group->member_count)
                            ); ?>
                </div>
                <div class="et-footer-item">
                    <span class="dashicons dashicons-businessman"></span>
                    <?php echo esc_html($group->admin_name); ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>
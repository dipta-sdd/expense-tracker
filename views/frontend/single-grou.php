<?php if (!defined('ABSPATH')) exit; ?>

<div class="et-single-group">
    <div class="et-group-header">
        <h1><?php echo esc_html($group->name); ?></h1>
        <span class="et-group-role <?php echo esc_attr($group->user_role); ?>">
            <?php echo esc_html(ucfirst($group->user_role)); ?>
        </span>
    </div>

    <div class="et-group-details">
        <div class="et-group-info">
            <h3><?php esc_html_e('Description', 'expense-tracker'); ?></h3>
            <p><?php echo wp_kses_post($group->description); ?></p>
        </div>

        <div class="et-group-meta">
            <div class="et-meta-item">
                <span class="dashicons dashicons-businessman"></span>
                <span class="et-meta-label"><?php esc_html_e('Admin:', 'expense-tracker'); ?></span>
                <span class="et-meta-value"><?php echo esc_html($group->admin_name); ?></span>
            </div>
            <div class="et-meta-item">
                <span class="dashicons dashicons-groups"></span>
                <span class="et-meta-label"><?php esc_html_e('Members:', 'expense-tracker'); ?></span>
                <span class="et-meta-value"><?php echo esc_html($group->member_count); ?></span>
            </div>
            <div class="et-meta-item">
                <span class="dashicons dashicons-calendar"></span>
                <span class="et-meta-label"><?php esc_html_e('Created:', 'expense-tracker'); ?></span>
                <span
                    class="et-meta-value"><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($group->created_at))); ?></span>
            </div>
        </div>
    </div>
</div>
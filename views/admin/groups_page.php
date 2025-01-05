<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap expense-tracker-wrap">
    <div class="et-flex et-flex-row et-flex-space-between">
        <h1 class="wp-heading-inline"><?php echo esc_html__('Groups', 'expense-tracker'); ?></h1>
        <a href="?page=expense-tracker-groups&action=new" class="page-title-action">
            <?php echo esc_html__('Add New Group', 'expense-tracker'); ?>
        </a>
    </div>
    <hr class="wp-header-end">

    <!-- Groups Grid -->
    <div class="et-grid et-grid-3">
        <!-- Active Groups Card -->
        <div class="et-card et-bg-light">
            <div class="et-summary-card">
                <div class="et-card-icon et-bg-secondary">
                    <span class="dashicons dashicons-groups et-text-primary"></span>
                </div>
                <div class="et-card-content">
                    <h3 class="et-text-tertiary"><?php echo esc_html__('Active Groups', 'expense-tracker'); ?></h3>
                    <p class="amount et-text-primary">5</p>
                </div>
            </div>
        </div>

        <!-- Total Members Card -->
        <div class="et-card et-bg-light">
            <div class="et-summary-card">
                <div class="et-card-icon et-bg-secondary">
                    <span class="dashicons dashicons-admin-users et-text-primary"></span>
                </div>
                <div class="et-card-content">
                    <h3 class="et-text-tertiary"><?php echo esc_html__('Total Members', 'expense-tracker'); ?></h3>
                    <p class="amount et-text-primary">15</p>
                </div>
            </div>
        </div>

        <!-- Total Budget Card -->
        <div class="et-card et-bg-light">
            <div class="et-summary-card">
                <div class="et-card-icon et-bg-secondary">
                    <span class="dashicons dashicons-money-alt et-text-primary"></span>
                </div>
                <div class="et-card-content">
                    <h3 class="et-text-tertiary"><?php echo esc_html__('Total Budget', 'expense-tracker'); ?></h3>
                    <p class="amount et-text-primary">$5,000</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Groups List -->
    <div class="et-card et-bg-light">
        <table class="et-table">
            <thead>
                <tr>
                    <th class="et-text-tertiary"><?php echo esc_html__('Group Name', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Members', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Budget', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Spent', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Status', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Actions', 'expense-tracker'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="et-text-tertiary">Family Budget</td>
                    <td class="et-text-secondary">4 members</td>
                    <td class="et-text-primary">$1,000</td>
                    <td class="et-text-secondary">$750</td>
                    <td>
                        <span class="et-badge et-bg-primary et-text-light">Active</span>
                    </td>
                    <td>
                        <div class="et-action-buttons">
                            <a href="#" class="button button-small"
                                title="<?php echo esc_attr__('View Details', 'expense-tracker'); ?>">
                                <span class="dashicons dashicons-visibility et-text-secondary"></span>
                            </a>
                            <a href="#" class="button button-small"
                                title="<?php echo esc_attr__('Edit', 'expense-tracker'); ?>">
                                <span class="dashicons dashicons-edit et-text-primary"></span>
                            </a>
                            <a href="#" class="button button-small"
                                title="<?php echo esc_attr__('Delete', 'expense-tracker'); ?>">
                                <span class="dashicons dashicons-trash et-text-danger"></span>
                            </a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        const GroupsPage = {
            init: function() {
                this.setupActions();
            },

            setupActions: function() {
                $('.et-action-buttons .button').on('click', function(e) {
                    e.preventDefault();
                    const action = $(this).find('.dashicons').attr('class').split(' ')[1];
                    const groupId = $(this).closest('tr').data('id');

                    switch (action) {
                        case 'dashicons-trash':
                            if (confirm(
                                    '<?php echo esc_js(__('Are you sure you want to delete this group?', 'expense-tracker')); ?>'
                                )) {
                                GroupsPage.deleteGroup(groupId);
                            }
                            break;
                        case 'dashicons-visibility':
                            window.location.href =
                                `?page=expense-tracker-groups&action=view&id=${groupId}`;
                            break;
                        case 'dashicons-edit':
                            window.location.href =
                                `?page=expense-tracker-groups&action=edit&id=${groupId}`;
                            break;
                    }
                });
            },

            deleteGroup: function(groupId) {
                $.ajax({
                    url: ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'delete_group',
                        nonce: '<?php echo wp_create_nonce('et_groups_nonce'); ?>',
                        group_id: groupId
                    },
                    success: function(response) {
                        if (response.success) {
                            $(`tr[data-id="${groupId}"]`).remove();
                        }
                    }
                });
            }
        };

        // Initialize groups page
        GroupsPage.init();
    });
</script>
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
                <tr class="sortable">
                    <th class="et-text-tertiary <?php echo $sort === 'name' ? $direction : ''; ?>" data-sort="name">
                        <span class="text">
                            <?php echo esc_html__('Group Name', 'expense-tracker'); ?>
                        </span>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc"></span>
                            <span class="sorting-indicator desc"></span>
                        </span>
                    </th>
                    <th class="et-text-tertiary <?php echo $sort === 'members' ? $direction : ''; ?>"
                        data-sort="members">
                        <span class="text">
                            <?php echo esc_html__('Members', 'expense-tracker'); ?>
                        </span>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc"></span>
                            <span class="sorting-indicator desc"></span>
                        </span>
                    </th>
                    <th class="et-text-tertiary <?php echo $sort === 'budget' ? $direction : ''; ?>" data-sort="budget">
                        <span class="text">
                            <?php echo esc_html__('Budget', 'expense-tracker'); ?>
                        </span>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc"></span>
                            <span class="sorting-indicator desc"></span>
                        </span>
                    </th>
                    <th class="et-text-tertiary <?php echo $sort === 'expense' ? $direction : ''; ?>"
                        data-sort="expense">
                        <span class="text">
                            <?php echo esc_html__('Spent', 'expense-tracker'); ?>
                        </span>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc"></span>
                            <span class="sorting-indicator desc"></span>
                        </span>
                    </th>
                    <th class="et-text-tertiary <?php echo $sort === 'status' ? $direction : ''; ?>" data-sort="status">
                        <span class="text">
                            <?php echo esc_html__('Status', 'expense-tracker'); ?>
                        </span>
                        <span class="sorting-indicators">
                            <span class="sorting-indicator asc"></span>
                            <span class="sorting-indicator desc"></span>
                        </span>
                    </th>
                    <th class="et-text-tertiary">
                        <?php echo esc_html__('Actions', 'expense-tracker'); ?>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($groups as $group) : ?>
                <tr data-id="<?php echo esc_attr($group->group_id); ?>">
                    <td class="et-text-tertiary" style="text-transform: capitalize;"><?php echo $group->name; ?></td>
                    <td class="et-text-secondary">
                        <?php echo $group->members ? $group->members . ' members' : 'No members'; ?></td>
                    <td class="et-text-primary"><?php echo $group->budget ? '$  ' . $group->budget : 'N/A'; ?></td>
                    <td class="et-text-secondary"><?php echo $group->expense ? '$ ' . $group->expense : 'N/A'; ?></td>
                    <td>
                        <span class="et-badge et-bg-primary et-text-light"><?php echo $group->status; ?></span>
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
                <?php endforeach; ?>
            </tbody>
        </table>

        <?php include_once 'pagination.php'; ?>
    </div>
    <?php

    ?>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {

    $('.et-action-buttons .button').on('click', function(e) {
        e.preventDefault();
        const action = $(this).find('.dashicons').attr('class').split(' ')[1];
        const groupId = $(this).closest('tr').data('id');
        console.log(action, groupId);
        switch (action) {
            case 'dashicons-trash':
                if (confirm(
                        '<?php echo esc_js(__('Are you sure you want to delete this group?', 'expense-tracker')); ?>'
                    )) {
                    deleteGroup(groupId);
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
    // },

    function deleteGroup(groupId) {
        $.ajax({
            url: '/wp-json/expense-tracker/v1/groups/' + groupId,
            type: 'DELETE',
            beforeSend: function(xhr) {
                xhr.setRequestHeader('X-WP-Nonce',
                    '<?php echo wp_create_nonce('wp_rest'); ?>');
            },
            success: function(response) {
                $(`tr[data-id="${groupId}"]`).remove();
            }
        });
    }
    $('.et-pagination-controls .et-per-page select').on('change', function(e) {
        e.preventDefault();
        const per_page = $(this).val();
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('per_page')) {
            urlParams.set('per_page', per_page);
        } else {
            urlParams.append('per_page', per_page);
        }
        urlParams.delete('p');
        window.location.href = '?' + urlParams.toString();
    });
    $('.et-pagination-controls .et-pagination-buttons a').on('click', function(e) {
        e.preventDefault();
        const page = $(this).data('page');
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('p')) {
            urlParams.set('p', page);
        } else {
            urlParams.append('p', page);
        }
        window.location.href = '?' + urlParams.toString();
    });
    $('.et-table thead .sortable th').on('click', function(e) {
        const sort = $(this).data('sort');
        const sortDirection = $(this).hasClass('asc') ? 'desc' : 'asc';
        let currentUrl = window.location.search;
        const urlParams = new URLSearchParams(currentUrl);
        if (urlParams.has('sort')) {
            urlParams.set('sort', sort);
        } else {
            urlParams.append('sort', sort);
        }
        if (urlParams.has('direction')) {
            urlParams.set('direction', sortDirection);
        } else {
            urlParams.append('direction', sortDirection);
        }
        urlParams.delete('p');
        window.location.href = '?' + urlParams.toString();
    });

});
</script>
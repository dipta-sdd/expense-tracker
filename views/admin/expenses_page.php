<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap expense-tracker-wrap">

    <h1 class="wp-heading-inline"><?php echo esc_html__('Expenses', 'expense-tracker'); ?></h1>
    <a href="?page=expense-tracker-expenses&action=new" class="page-title-action">
        <?php echo esc_html__('Add New', 'expense-tracker'); ?>
    </a>
    <hr class="wp-header-end">

    <!-- Filters -->
    <div class="et-card et-bg-light">
        <div class="et-grid et-grid-4">
            <div class="et-form-group">
                <label for="date-from"><?php echo esc_html__('From', 'expense-tracker'); ?></label>
                <input type="date" id="date-from" class="regular-text">
            </div>
            <div class="et-form-group">
                <label for="date-to"><?php echo esc_html__('To', 'expense-tracker'); ?></label>
                <input type="date" id="date-to" class="regular-text">
            </div>
            <div class="et-form-group">
                <label for="category"><?php echo esc_html__('Category', 'expense-tracker'); ?></label>
                <select id="category" class="regular-text">
                    <option value=""><?php echo esc_html__('All Categories', 'expense-tracker'); ?></option>
                    <option value="1">Business</option>
                    <option value="2">Personal</option>
                </select>
            </div>
            <div class="et-form-group">
                <label for="search"><?php echo esc_html__('Search', 'expense-tracker'); ?></label>
                <input type="text" id="search" class="regular-text"
                    placeholder="<?php echo esc_attr__('Search expenses...', 'expense-tracker'); ?>">
            </div>
        </div>
        <div class="et-actions">
            <button class="button button-primary" id="apply-filters">
                <span class="dashicons dashicons-filter"></span>
                <?php echo esc_html__('Apply Filters', 'expense-tracker'); ?>
            </button>
            <button class="button button-secondary" id="reset-filters">
                <span class="dashicons dashicons-dismiss"></span>
                <?php echo esc_html__('Reset', 'expense-tracker'); ?>
            </button>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="et-grid et-grid-3">
        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-money-alt et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Total Amount', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">$2,345.67</p>
                <p class="period et-text-secondary"><?php echo esc_html__('Current Filter', 'expense-tracker'); ?></p>
            </div>
        </div>

        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-chart-bar et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Average', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">$234.57</p>
                <p class="period et-text-secondary"><?php echo esc_html__('Per Transaction', 'expense-tracker'); ?></p>
            </div>
        </div>

        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-calculator et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Count', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">10</p>
                <p class="period et-text-secondary"><?php echo esc_html__('Transactions', 'expense-tracker'); ?></p>
            </div>
        </div>
    </div>

    <!-- Expenses Table -->
    <div class="et-card et-bg-light">
        <table class="et-table">
            <thead>
                <tr>
                    <th class="et-text-tertiary"><?php echo esc_html__('Date', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Description', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Category', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Amount', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Receipt', 'expense-tracker'); ?></th>
                    <th class="et-text-tertiary"><?php echo esc_html__('Actions', 'expense-tracker'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="et-text-secondary">2024-03-20</td>
                    <td class="et-text-tertiary">Office Supplies</td>
                    <td>
                        <span class="et-badge et-bg-primary et-text-light">Business</span>
                    </td>
                    <td class="et-text-primary et-text-right">$45.99</td>
                    <td>
                        <a href="#" class="button button-small">
                            <span class="dashicons dashicons-paperclip et-text-secondary"></span>
                        </a>
                    </td>
                    <td>
                        <div class="et-action-buttons">
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
                <!-- Add more rows as needed -->
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="tablenav bottom">
            <div class="tablenav-pages">
                <span class="displaying-num">10 items</span>
                <span class="pagination-links">
                    <a class="first-page button" href="#"><span class="screen-reader-text">First page</span><span
                            aria-hidden="true">«</span></a>
                    <a class="prev-page button" href="#"><span class="screen-reader-text">Previous page</span><span
                            aria-hidden="true">‹</span></a>
                    <span class="paging-input">
                        <label for="current-page-selector" class="screen-reader-text">Current Page</label>
                        <input class="current-page" id="current-page-selector" type="text" name="paged" value="1"
                            size="1" aria-describedby="table-paging">
                        <span class="tablenav-paging-text"> of <span class="total-pages">2</span></span>
                    </span>
                    <a class="next-page button" href="#"><span class="screen-reader-text">Next page</span><span
                            aria-hidden="true">›</span></a>
                    <a class="last-page button" href="#"><span class="screen-reader-text">Last page</span><span
                            aria-hidden="true">»</span></a>
                </span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
jQuery(document).ready(function($) {
    const ExpensePage = {
        init: function() {
            this.setupFilters();
            this.setupActions();
        },

        setupFilters: function() {
            $('#apply-filters').on('click', function(e) {
                e.preventDefault();
                ExpensePage.applyFilters();
            });

            $('#reset-filters').on('click', function(e) {
                e.preventDefault();
                ExpensePage.resetFilters();
            });
        },

        setupActions: function() {
            $('.et-action-buttons .button').on('click', function(e) {
                e.preventDefault();
                const action = $(this).find('.dashicons').hasClass('dashicons-trash') ?
                    'delete' : 'edit';
                const expenseId = $(this).closest('tr').data('id');

                if (action === 'delete') {
                    if (confirm(
                            '<?php echo esc_js(__('Are you sure you want to delete this expense?', 'expense-tracker')); ?>'
                        )) {
                        ExpensePage.deleteExpense(expenseId);
                    }
                } else {
                    window.location.href =
                        `?page=expense-tracker-expenses&action=edit&id=${expenseId}`;
                }
            });
        },

        applyFilters: function() {
            const filters = {
                dateFrom: $('#date-from').val(),
                dateTo: $('#date-to').val(),
                category: $('#category').val(),
                search: $('#search').val()
            };

            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'filter_expenses',
                    nonce: '<?php echo wp_create_nonce('et_expenses_nonce'); ?>',
                    filters: filters
                },
                success: function(response) {
                    if (response.success) {
                        // Update table and summary cards
                        console.log('Filters applied');
                    }
                }
            });
        },

        resetFilters: function() {
            $('#date-from, #date-to, #search').val('');
            $('#category').val('');
            this.applyFilters();
        },

        deleteExpense: function(expenseId) {
            $.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'delete_expense',
                    nonce: '<?php echo wp_create_nonce('et_expenses_nonce'); ?>',
                    expense_id: expenseId
                },
                success: function(response) {
                    if (response.success) {
                        // Remove row and update summary
                        $(`tr[data-id="${expenseId}"]`).remove();
                    }
                }
            });
        }
    };

    // Initialize expense page
    ExpensePage.init();
});
</script>
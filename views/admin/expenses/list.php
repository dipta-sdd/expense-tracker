<?php
if (!defined('ABSPATH')) exit;

$categories = expense_tracker_init()->getModule('categories')->getCategories();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php esc_html_e('Expenses', 'expense-tracker'); ?></h1>
    <a href="<?php echo esc_url(admin_url('admin.php?page=expense-tracker-add-new')); ?>" class="page-title-action">
        <?php esc_html_e('Add New', 'expense-tracker'); ?>
    </a>

    <hr class="wp-header-end">

    <div class="tablenav top">
        <div class="alignleft actions">
            <select id="filter-category">
                <option value=""><?php esc_html_e('All Categories', 'expense-tracker'); ?></option>
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category['id']); ?>">
                            <?php echo esc_html($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <select id="filter-status">
                <option value=""><?php esc_html_e('All Statuses', 'expense-tracker'); ?></option>
                <option value="pending"><?php esc_html_e('Pending', 'expense-tracker'); ?></option>
                <option value="approved"><?php esc_html_e('Approved', 'expense-tracker'); ?></option>
                <option value="rejected"><?php esc_html_e('Rejected', 'expense-tracker'); ?></option>
            </select>

            <select id="sort-by">
                <option value=""><?php esc_html_e('Sort By', 'expense-tracker'); ?></option>
                <option value="date_asc"><?php esc_html_e('Date Ascending', 'expense-tracker'); ?></option>
                <option value="date_desc"><?php esc_html_e('Date Descending', 'expense-tracker'); ?></option>
                <option value="amount_asc"><?php esc_html_e('Amount Ascending', 'expense-tracker'); ?></option>
                <option value="amount_desc"><?php esc_html_e('Amount Descending', 'expense-tracker'); ?></option>
            </select>

            <button id="filter-expenses" class="button"><?php esc_html_e('Filter', 'expense-tracker'); ?></button>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-date"><?php esc_html_e('Date', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-amount"><?php esc_html_e('Amount', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-description">
                    <?php esc_html_e('Description', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-category"><?php esc_html_e('Category', 'expense-tracker'); ?>
                </th>
                <th scope="col" class="manage-column column-status"><?php esc_html_e('Status', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-created_by"><?php esc_html_e('Created By', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-updated_by"><?php esc_html_e('Updated By', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-actions"><?php esc_html_e('Actions', 'expense-tracker'); ?></th>
            </tr>
        </thead>

        <tbody id="the-list">
            <!-- Expenses will be populated dynamically -->
        </tbody>
    </table>
</div>

<script>
    jQuery(document).ready(function($) {
        function loadExpenses(filters = {}) {
            $.ajax({
                url: '<?php echo esc_url(get_rest_url(null, 'expense-tracker/v1/expenses')); ?>',
                method: 'GET',
                data: filters,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>');
                },
                success: function(response) {
                    console.log('Expenses loaded:', response);
                    $('#the-list').empty();
                    if (response.length > 0) {
                        response.forEach(function(expense) {
                            const row = $(`<tr data-id="${expense.id}">
                                            <td>${expense.date}</td>
                                            <td>${expense.amount}</td>
                                            <td>${expense.description}</td>
                                            <td>${expense.category_name}</td>
                                            <td>${expense.status}</td>
                                            <td>${expense.created_by_name}</td>
                                            <td>${expense.updated_by_name}</td>
                                            <td>
                                                <a href="<?php echo esc_url(admin_url('admin.php?page=expense-tracker-add-new&id=')); ?>${expense.id}">
                                                    <?php esc_html_e('Edit', 'expense-tracker'); ?>
                                                </a> |
                                                <a href="#" class="delete-expense" data-id="${expense.id}">
                                                    <?php esc_html_e('Delete', 'expense-tracker'); ?>
                                                </a>
                                            </td>
                                        </tr>`);
                            $('#the-list').append(row);
                        });
                    } else {
                        $('#the-list').append($('<tr ><td colspan="8"><?php esc_html_e('No expenses found.', 'expense-tracker'); ?></td></tr>'));
                    }
                },
                error: function(error) {
                    console.error('Error loading expenses:', error);
                }
            });
        }

        loadExpenses();

        $('#filter-expenses').on('click', function(e) {
            e.preventDefault();
            const filters = {
                category_id: $('#filter-category').val(),
                status: $('#filter-status').val(),
                sort_by: $('#sort-by').val()
            };
            loadExpenses(filters);
        });

        $(document).on('click', '.delete-expense', function(e) {
            e.preventDefault();
            const expenseId = $(this).data('id');

            if (confirm('Are you sure you want to delete this expense?')) {
                $.ajax({
                    url: '<?php echo esc_url(get_rest_url(null, 'expense-tracker/v1/expenses/')); ?>' + expenseId,
                    method: 'DELETE',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', '<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>');
                    },
                    success: function(response) {
                        console.log('Expense deleted:', response);
                        $(`tr[data-id="${expenseId}"]`).remove();
                    },
                    error: function(error) {
                        console.error('Error deleting expense:', error);
                    }
                });
            }
        });
    });
</script>
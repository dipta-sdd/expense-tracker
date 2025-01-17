<?php
if (!defined('ABSPATH')) exit;

$categories = expense_tracker_init()->getModule('categories')->getCategories();
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Expenses', 'expense-tracker'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=expense-tracker-add-new'); ?>" class="page-title-action">
        <?php _e('Add New', 'expense-tracker'); ?>
    </a>

    <hr class="wp-header-end">

    <div class="tablenav top">
        <div class="alignleft actions">
            <select id="filter-category">
                <option value=""><?php _e('All Categories', 'expense-tracker'); ?></option>
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category['id']); ?>">
                            <?php echo esc_html($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <select id="filter-status">
                <option value=""><?php _e('All Statuses', 'expense-tracker'); ?></option>
                <option value="pending"><?php _e('Pending', 'expense-tracker'); ?></option>
                <option value="approved"><?php _e('Approved', 'expense-tracker'); ?></option>
                <option value="rejected"><?php _e('Rejected', 'expense-tracker'); ?></option>
            </select>

            <select id="sort-by">
                <option value=""><?php _e('Sort By', 'expense-tracker'); ?></option>
                <option value="date_asc"><?php _e('Date Ascending', 'expense-tracker'); ?></option>
                <option value="date_desc"><?php _e('Date Descending', 'expense-tracker'); ?></option>
                <option value="amount_asc"><?php _e('Amount Ascending', 'expense-tracker'); ?></option>
                <option value="amount_desc"><?php _e('Amount Descending', 'expense-tracker'); ?></option>
            </select>

            <button id="filter-expenses" class="button"><?php _e('Filter', 'expense-tracker'); ?></button>
        </div>
    </div>

    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th scope="col" class="manage-column column-date"><?php _e('Date', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-amount"><?php _e('Amount', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-description">
                    <?php _e('Description', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-category"><?php _e('Category', 'expense-tracker'); ?>
                </th>
                <th scope="col" class="manage-column column-status"><?php _e('Status', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-created_by"><?php _e('Created By', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-updated_by"><?php _e('Updated By', 'expense-tracker'); ?></th>
                <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'expense-tracker'); ?></th>
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
                url: '<?php echo get_rest_url(null, 'expense-tracker/v1/expenses'); ?>',
                method: 'GET',
                data: filters,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
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
                                                <a href="<?php echo admin_url('admin.php?page=expense-tracker-edit&id='); ?>${expense.id}">
                                                    <?php _e('Edit', 'expense-tracker'); ?>
                                                </a> |
                                                <a href="#" class="delete-expense" data-id="${expense.id}">
                                                    <?php _e('Delete', 'expense-tracker'); ?>
                                                </a>
                                            </td>
                                        </tr>`);
                            $('#the-list').append(row);
                        });
                    } else {
                        $('#the-list').append($('<tr ><td colspan="8"><?php _e('No expenses found.', 'expense-tracker'); ?></td></tr>'));
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
                    url: '<?php echo get_rest_url(null, 'expense-tracker/v1/expenses/'); ?>' + expenseId,
                    method: 'DELETE',
                    beforeSend: function(xhr) {
                        xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
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
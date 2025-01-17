<div class="expense-tracker-form-container">
    <form id="expense-submission-form" class="expense-tracker-form">
        <div class="form-group">
            <label for="date"><?php _e('Date', 'expense-tracker'); ?></label>
            <input type="date" name="date" id="date" class="regular-text" value="<?php echo date('Y-m-d'); ?>" required>
        </div>
        <div class="form-group">
            <label for="amount"><?php _e('Amount', 'expense-tracker'); ?></label>
            <input type="number" name="amount" id="amount" class="regular-text" step="0.01" required>
        </div>
        <div class="form-group">
            <label for="description"><?php _e('Description', 'expense-tracker'); ?></label>
            <textarea name="description" id="description" class="regular-text" required></textarea>
        </div>
        <div class="form-group <?php echo $atts['hide_category'] ? 'expense-tracker-d-none' : ''; ?>">
            <label for="category_id"><?php _e('Category', 'expense-tracker'); ?></label>
            <select name="category_id" id="category_id" required>
                <option value=""><?php _e('Select Category', 'expense-tracker'); ?></option>
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category['id']); ?>" <?php selected($atts['category_id'], $category['id']); ?>>
                            <?php echo esc_html($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>
        <div class="form-group">
            <button type="submit" class="button button-primary"><?php _e('Submit Expense', 'expense-tracker'); ?></button>
        </div>
    </form>
    <div id="expense-submission-message"></div>
</div>
<script>
    jQuery(document).ready(function($) {
        $('#expense-submission-form').on('submit', function(e) {
            e.preventDefault();
            const formData = $(this).serializeArray();
            $.ajax({
                url: '<?php echo get_rest_url(null, 'expense-tracker/v1/expenses'); ?>',
                method: 'POST',
                data: formData,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
                },
                success: function(response) {
                    $('#expense-submission-message').html('<div class="notice notice-success is-dismissible"><p><?php _e('Expense submitted successfully!', 'expense-tracker'); ?></p></div>');
                    $('#expense-submission-form')[0].reset();
                    if ($('#expense-tracker-list').length) {
                        if ($('#filter-category').val()) {
                            const filters = {
                                category_id: $('#filter-category').val(),
                                status: $('#filter-status').val(),
                                sort_by: $('#sort-by').val()
                            };
                            loadExpenses(filters);
                        } else if ($('table.expense-tracker-table').data('category-id') == response.category_id) {
                            loadExpenses({
                                category_id: response.category_id
                            });
                        } else if ($('table.expense-tracker-table').data('category-id') == undefined) {
                            loadExpenses();
                        }
                    }

                },
                error: function(error) {
                    $('#expense-submission-message').html('<div class="notice notice-error is-dismissible"><p><?php _e('Error submitting expense.', 'expense-tracker'); ?></p></div>');
                    console.error('Error submitting expense:', error);
                }
            });
        });

        function loadExpenses(filters = {}) {
            $.ajax({
                url: '<?php echo get_rest_url(null, 'expense-tracker/v1/expenses'); ?>',
                method: 'GET',
                data: filters,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
                },
                success: function(response) {
                    // console.log('Expenses loaded:', response);
                    $('#expense-tracker-list').empty();
                    if (response.length > 0) {
                        response.forEach(function(expense) {
                            const row = $(`<tr>
                                                    <td>${formatDateTime(expense.date , '<?php echo get_option('date_format'); ?>')}</td>
                                                    <td>${expense.amount}</td>
                                                    <td>${expense.description}</td>
                                                    <td>${expense.category_name}</td>
                                                    <td>${expense.status}</td>
                                                    <td>${expense.created_by_name}</td>
                                                    <td>${expense.updated_by_name}</td>
                                                </tr>`);
                            $('#expense-tracker-list').append(row);
                        });
                    } else {
                        $('#expense-tracker-list').append($('<tr><td colspan="7"><?php _e('No expenses found.', 'expense-tracker'); ?></td></tr>'));
                    }
                },
                error: function(error) {
                    console.error('Error loading expenses:', error);
                }
            });
        }
    });
</script>
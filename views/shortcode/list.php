<div class="expense-tracker-shortcode">
    <?php if ($filter) : ?>
        <div class="expense-tracker-filters">
            <select id="filter-category" <?php echo $atts['category_id'] ? 'disabled' : ''; ?>>
                <option value=""><?php _e('All Categories', 'expense-tracker'); ?></option>
                <?php if ($categories) : ?>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category['id']); ?>" <?php selected($atts['category_id'], $category['id']); ?>>
                            <?php echo esc_html($category['name']); ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>

            <select id="filter-status">
                <option value=""><?php _e('All Statuses', 'expense-tracker'); ?></option>
                <option value="pending" <?php selected($atts['status'], 'pending'); ?>><?php _e('Pending', 'expense-tracker'); ?></option>
                <option value="approved" <?php selected($atts['status'], 'approved'); ?>><?php _e('Approved', 'expense-tracker'); ?></option>
                <option value="rejected" <?php selected($atts['status'], 'rejected'); ?>><?php _e('Rejected', 'expense-tracker'); ?></option>
            </select>

            <select id="sort-by">
                <option value=""><?php _e('Sort By', 'expense-tracker'); ?></option>
                <option value="date_asc" <?php selected($atts['sort_by'], 'date_asc'); ?>><?php _e('Date Ascending', 'expense-tracker'); ?></option>
                <option value="date_desc" <?php selected($atts['sort_by'], 'date_desc'); ?>><?php _e('Date Descending', 'expense-tracker'); ?></option>
                <option value="amount_asc" <?php selected($atts['sort_by'], 'amount_asc'); ?>><?php _e('Amount Ascending', 'expense-tracker'); ?></option>
                <option value="amount_desc" <?php selected($atts['sort_by'], 'amount_desc'); ?>><?php _e('Amount Descending', 'expense-tracker'); ?></option>
            </select>
            <button id="filter-expenses" class="button"><?php _e('Filter', 'expense-tracker'); ?></button>
        </div>
    <?php endif; ?>
    <table class="expense-tracker-table" <?php echo $atts['category_id'] ? 'data-category-id="' . $atts['category_id'] . '"' : ''; ?>>
        <thead>
            <tr>
                <th><?php _e('Date', 'expense-tracker'); ?></th>
                <th><?php _e('Amount', 'expense-tracker'); ?></th>
                <th><?php _e('Description', 'expense-tracker'); ?></th>
                <th><?php _e('Category', 'expense-tracker'); ?></th>
                <th><?php _e('Status', 'expense-tracker'); ?></th>
                <th><?php _e('Created By', 'expense-tracker'); ?></th>
                <th><?php _e('Updated By', 'expense-tracker'); ?></th>
            </tr>
        </thead>
        <tbody id="expense-tracker-list">
            <?php if ($expenses) : ?>
                <?php foreach ($expenses as $expense) : ?>
                    <tr>
                        <td><?php echo esc_html(date_i18n(get_option('date_format'), strtotime($expense['date']))); ?></td>
                        <td><?php echo esc_html($expense['amount']); ?></td>
                        <td><?php echo esc_html($expense['description']); ?></td>
                        <td><?php echo esc_html($expense['category_name']); ?></td>
                        <td><?php echo esc_html($expense['status']); ?></td>
                        <td><?php echo esc_html($expense['created_by_name']); ?></td>
                        <td><?php echo esc_html($expense['updated_by_name']); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7"><?php _e('No expenses found.', 'expense-tracker'); ?></td>
                </tr>
            <?php endif; ?>
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

        $('#filter-expenses').on('click', function(e) {
            e.preventDefault();
            const filters = {
                category_id: $('#filter-category').val(),
                status: $('#filter-status').val(),
                sort_by: $('#sort-by').val()
            };
            loadExpenses(filters);
        });
    });
</script>
<?php
if (!defined('ABSPATH')) exit;

$categories = expense_tracker_init()->getModule('categories')->getCategories();
?>

<div class="wrap">
    <h1><?php _e('Edit Expense', 'expense-tracker'); ?></h1>

    <form id="edit-expense-form" method="post" action="" class="expense-form">
        <?php wp_nonce_field('update_expense', 'expense_nonce'); ?>
        <input type="hidden" name="expense_id" id="expense_id" value="<?php echo esc_attr($expense['id']); ?>">

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="amount"><?php _e('Amount', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="number" step="0.01" name="amount" id="amount" class="regular-text"
                        value="<?php echo esc_attr($expense['amount']); ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="description"><?php _e('Description', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <textarea name="description" id="description" class="large-text" rows="3"
                        required><?php echo esc_textarea($expense['description']); ?></textarea>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="category"><?php _e('Category', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <select name="category_id" id="category" required>
                        <option value=""><?php _e('Select Category', 'expense-tracker'); ?></option>
                        <?php if ($categories) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category['id']); ?>" <?php selected($expense['category_id'], $category['id']); ?>>
                                    <?php echo esc_html($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="date"><?php _e('Date', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="date" name="date" id="date" class="regular-text"
                        value="<?php echo esc_attr($expense['date']); ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="status"><?php _e('Status', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <select name="status" id="status" required>
                        <option value="pending" <?php selected($expense['status'], 'pending'); ?>>
                            <?php _e('Pending', 'expense-tracker'); ?>
                        </option>
                        <option value="approved" <?php selected($expense['status'], 'approved'); ?>>
                            <?php _e('Approved', 'expense-tracker'); ?>
                        </option>
                        <option value="rejected" <?php selected($expense['status'], 'rejected'); ?>>
                            <?php _e('Rejected', 'expense-tracker'); ?>
                        </option>
                    </select>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Update Expense', 'expense-tracker')); ?>
    </form>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#edit-expense-form').on('submit', function(e) {
            e.preventDefault();

            const expenseId = $('#expense_id').val();
            const formData = $(this).serializeObject();

            $.ajax({
                url: '<?php echo get_rest_url(null, 'expense-tracker/v1/expenses/'); ?>' + expenseId,
                method: 'PUT',
                data: formData,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo wp_create_nonce('wp_rest'); ?>');
                },
                success: function(response) {
                    console.log('Expense updated:', response);
                    window.location.href = '<?php echo admin_url('admin.php?page=expense-tracker'); ?>';
                },
                error: function(error) {
                    console.error('Error updating expense:', error);
                }
            });
        });

        $.fn.serializeObject = function() {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function() {
                if (o[this.name]) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };
    });
</script>
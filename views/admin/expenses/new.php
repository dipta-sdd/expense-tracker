<?php
if (!defined('ABSPATH')) exit;

$categories = expense_tracker_init()->getModule('categories')->getCategories();
?>

<div class="wrap">
    <h1><?php esc_html_e('Add New Expense', 'expense-tracker'); ?></h1>

    <form id="add-expense-form" method="post" action="" class="expense-form">
        <?php wp_nonce_field('create_expense', 'expense_nonce'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="amount"><?php esc_html_e('Amount', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="number" step="0.01" name="amount" id="amount" class="regular-text" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="description"><?php esc_html_e('Description', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <textarea name="description" id="description" class="large-text" rows="3" required></textarea>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="category"><?php esc_html_e('Category', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <select name="category_id" id="category" required>
                        <option value=""><?php esc_html_e('Select Category', 'expense-tracker'); ?></option>
                        <?php if ($categories) : ?>
                            <?php foreach ($categories as $category) : ?>
                                <option value="<?php echo esc_attr($category['id']); ?>">
                                    <?php echo esc_html($category['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="date"><?php esc_html_e('Date', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="date" name="date" id="date" class="regular-text" value="<?php echo esc_attr(gmdate('Y-m-d')); ?>"
                        required>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Add Expense', 'expense-tracker')); ?>
    </form>
</div>

<script>
    jQuery(document).ready(function($) {
        $('#add-expense-form').on('submit', function(e) {
            e.preventDefault();

            const formData = $(this).serializeObject();

            $.ajax({
                url: '<?php echo esc_url(get_rest_url(null, 'expense-tracker/v1/expenses')); ?>',
                method: 'POST',
                data: formData,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>');
                },
                success: function(response) {
                    console.log('Expense created:', response);
                    window.location.href = '<?php echo esc_url(admin_url('admin.php?page=expense-tracker')); ?>';
                },
                error: function(error) {
                    console.error('Error creating expense:', error);
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
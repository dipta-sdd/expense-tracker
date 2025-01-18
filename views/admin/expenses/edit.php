<?php
if (!defined('ABSPATH')) exit;
$expense_id = intval($_GET['id']);
$categories = expense_tracker_init()->getModule('categories')->getCategories();
$expense = expense_tracker_init()->getModule('expenses')->getExpense($expense_id);
// echo json_encode($expense);
?>

<div class="wrap">
    <h1><?php esc_html_e('Edit Expense', 'expense-tracker'); ?></h1>

    <form id="edit-expense-form" method="post" action="" class="expense-form">
        <input type="hidden" name="expense_id" id="expense_id" value="<?php echo esc_attr($expense['id']); ?>">

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="amount"><?php esc_html_e('Amount', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="number" step="0.01" name="amount" id="amount" class="regular-text"
                        value="<?php echo esc_attr($expense['amount']); ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="description"><?php esc_html_e('Description', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <textarea name="description" id="description" class="large-text" rows="3"
                        required><?php echo esc_textarea($expense['description']); ?></textarea>
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
                    <label for="date"><?php esc_html_e('Date', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="date" name="date" id="date" class="regular-text"
                        value="<?php echo esc_attr($expense['date']); ?>" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="status"><?php esc_html_e('Status', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <select name="status" id="status" required>
                        <option value="Pending" <?php echo $expense['status'] == 'Pending' ? 'selected' : ''; ?>>
                            <?php esc_html_e('Pending', 'expense-tracker'); ?>
                        </option>
                        <option value="Approved" <?php echo $expense['status'] == 'Approved' ? 'selected' : ''; ?>>
                            <?php esc_html_e('Approved', 'expense-tracker'); ?>
                        </option>
                        <option value="Rejected" <?php echo $expense['status'] == 'Rejected' ? 'selected' : ''; ?>>
                            <?php esc_html_e('Rejected', 'expense-tracker'); ?>
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
            const formData = $(this).serializeArray();
            // console.log(formData);
            $.ajax({
                url: '<?php echo esc_url(get_rest_url(null, 'expense-tracker/v1/expenses/')); ?>' + expenseId,
                method: 'PUT',
                data: formData,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>');
                },
                success: function(response) {
                    console.log('Expense updated:', response);
                    window.location.href = '<?php echo esc_url(admin_url('admin.php?page=expense-tracker')); ?>';
                },
                error: function(error) {
                    console.error('Error updating expense:', error);
                }
            });
        });

    });
</script>
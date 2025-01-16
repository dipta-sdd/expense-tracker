<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1><?php _e('Edit Expense', 'expense-tracker'); ?></h1>

    <form method="post" action="" class="expense-form">
        <?php wp_nonce_field('update_expense', 'expense_nonce'); ?>
        <input type="hidden" name="expense_id" value="<?php echo esc_attr($expense['id']); ?>">

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
                        <!-- Categories will be populated dynamically -->
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
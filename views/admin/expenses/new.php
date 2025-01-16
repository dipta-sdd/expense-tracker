<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1><?php _e('Add New Expense', 'expense-tracker'); ?></h1>

    <form method="post" action="" class="expense-form">
        <?php wp_nonce_field('create_expense', 'expense_nonce'); ?>

        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="amount"><?php _e('Amount', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <input type="number" step="0.01" name="amount" id="amount" class="regular-text" required>
                </td>
            </tr>

            <tr>
                <th scope="row">
                    <label for="description"><?php _e('Description', 'expense-tracker'); ?></label>
                </th>
                <td>
                    <textarea name="description" id="description" class="large-text" rows="3" required></textarea>
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
                    <input type="date" name="date" id="date" class="regular-text" value="<?php echo date('Y-m-d'); ?>"
                        required>
                </td>
            </tr>
        </table>

        <?php submit_button(__('Add Expense', 'expense-tracker')); ?>
    </form>
</div>
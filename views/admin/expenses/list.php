<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Expenses', 'expense-tracker'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=expense-tracker-add-new'); ?>" class="page-title-action">
        <?php _e('Add New', 'expense-tracker'); ?>
    </a>

    <hr class="wp-header-end">

    <form method="get">
        <input type="hidden" name="page" value="expense-tracker">

        <div class="tablenav top">
            <!-- Filters -->
            <div class="alignleft actions">
                <select name="category">
                    <option value=""><?php _e('All Categories', 'expense-tracker'); ?></option>
                    <!-- Categories will be populated dynamically -->
                </select>

                <select name="status">
                    <option value=""><?php _e('All Statuses', 'expense-tracker'); ?></option>
                    <option value="pending"><?php _e('Pending', 'expense-tracker'); ?></option>
                    <option value="approved"><?php _e('Approved', 'expense-tracker'); ?></option>
                    <option value="rejected"><?php _e('Rejected', 'expense-tracker'); ?></option>
                </select>

                <?php submit_button(__('Filter', 'expense-tracker'), 'secondary', 'filter', false); ?>
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
                    <th scope="col" class="manage-column column-actions"><?php _e('Actions', 'expense-tracker'); ?></th>
                </tr>
            </thead>

            <tbody id="the-list">
                <!-- Expenses will be populated dynamically -->
            </tbody>
        </table>
    </form>
</div>
<?php
if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap expense-tracker-wrap">
    <h1><?php echo esc_html__('Expense Tracker Dashboard', 'expense-tracker'); ?></h1>

    <!-- Summary Cards -->
    <div class="et-grid et-grid-3">
        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-money-alt et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Total Expenses', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">$<?php echo number_format(1234.56, 2); ?></p>
                <p class="period et-text-secondary"><?php echo esc_html__('This Month', 'expense-tracker'); ?></p>
            </div>
        </div>

        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-groups et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Active Groups', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">5</p>
                <p class="period et-text-secondary"><?php echo esc_html__('Total Groups', 'expense-tracker'); ?></p>
            </div>
        </div>

        <div class="et-card et-summary-card et-bg-light">
            <div class="et-card-icon et-bg-secondary">
                <span class="dashicons dashicons-chart-bar et-text-primary"></span>
            </div>
            <div class="et-card-content">
                <h3 class="et-text-tertiary"><?php echo esc_html__('Budget Status', 'expense-tracker'); ?></h3>
                <p class="amount et-text-primary">75%</p>
                <p class="period et-text-secondary"><?php echo esc_html__('Budget Used', 'expense-tracker'); ?></p>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="et-card et-bg-light">
        <h2 class="et-text-tertiary"><?php echo esc_html__('Quick Actions', 'expense-tracker'); ?></h2>
        <div class="et-actions">
            <a href="?page=expense-tracker-expenses&action=new" class="button button-primary">
                <span class="dashicons dashicons-plus-alt"></span>
                <?php echo esc_html__('Add Expense', 'expense-tracker'); ?>
            </a>
            <a href="?page=expense-tracker-groups&action=new" class="button button-secondary">
                <span class="dashicons dashicons-groups"></span>
                <?php echo esc_html__('Create Group', 'expense-tracker'); ?>
            </a>
            <a href="?page=expense-tracker-expenses" class="button button-secondary">
                <span class="dashicons dashicons-list-view"></span>
                <?php echo esc_html__('View All Expenses', 'expense-tracker'); ?>
            </a>
        </div>
    </div>

    <!-- Recent Expenses -->
    <div class="et-card et-bg-light">
        <h2 class="et-text-tertiary"><?php echo esc_html__('Recent Expenses', 'expense-tracker'); ?></h2>
        <table class="et-table">
            <thead>
                <tr class="et-text-tertiary">
                    <th><?php echo esc_html__('Date', 'expense-tracker'); ?></th>
                    <th><?php echo esc_html__('Description', 'expense-tracker'); ?></th>
                    <th><?php echo esc_html__('Category', 'expense-tracker'); ?></th>
                    <th><?php echo esc_html__('Amount', 'expense-tracker'); ?></th>
                    <th><?php echo esc_html__('Actions', 'expense-tracker'); ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="et-text-secondary">2024-03-20</td>
                    <td class="et-text-tertiary">Office Supplies</td>
                    <td>
                        <span class="et-badge et-bg-success et-text-light">Business</span>
                    </td>
                    <td class="et-text-primary et-text-right">$45.99</td>
                    <td>
                        <div class="et-action-buttons">
                            <a href="#" class="button button-small">
                                <span class="dashicons dashicons-edit et-text-info"></span>
                            </a>
                            <a href="#" class="button button-small">
                                <span class="dashicons dashicons-trash et-text-danger"></span>
                            </a>
                        </div>
                    </td>
                </tr>
                <!-- Add more rows as needed -->
            </tbody>
        </table>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {

    });
</script>
<?php
if (!defined('ABSPATH')) exit;
?>

<div class="wrap">
    <h1><?php _e('Expense Reports', 'expense-tracker'); ?></h1>

    <!-- Filters -->
    <div class="postbox" style="padding: 15px; margin-top: 15px;">
        <form method="get">
            <input type="hidden" name="page" value="expense-reports">

            <div class="report-filters">
                <select name="period">
                    <option value="this_month"><?php _e('This Month', 'expense-tracker'); ?></option>
                    <option value="last_month"><?php _e('Last Month', 'expense-tracker'); ?></option>
                    <option value="this_year"><?php _e('This Year', 'expense-tracker'); ?></option>
                    <option value="custom"><?php _e('Custom Range', 'expense-tracker'); ?></option>
                </select>

                <div class="date-range" style="display: none;">
                    <input type="date" name="start_date">
                    <input type="date" name="end_date">
                </div>

                <select name="category">
                    <option value=""><?php _e('All Categories', 'expense-tracker'); ?></option>
                    <!-- Categories will be populated dynamically -->
                </select>

                <?php submit_button(__('Generate Report', 'expense-tracker'), 'primary', 'generate_report', false); ?>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="report-summary"
        style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
        <div class="postbox" style="padding: 15px;">
            <h3><?php _e('Total Expenses', 'expense-tracker'); ?></h3>
            <p class="total-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php _e('Average per Day', 'expense-tracker'); ?></h3>
            <p class="average-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php _e('Highest Expense', 'expense-tracker'); ?></h3>
            <p class="highest-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php _e('Number of Transactions', 'expense-tracker'); ?></h3>
            <p class="transaction-count">
                <!-- Populated dynamically -->
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="report-charts">
        <div class="postbox" style="padding: 15px; margin-bottom: 20px;">
            <h3><?php _e('Expenses Over Time', 'expense-tracker'); ?></h3>
            <canvas id="expenses-chart"></canvas>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php _e('Expenses by Category', 'expense-tracker'); ?></h3>
            <canvas id="category-chart"></canvas>
        </div>
    </div>
</div>

<script>
    jQuery(document).ready(function($) {
        $('select[name="period"]').on('change', function() {
            if ($(this).val() === 'custom') {
                $('.date-range').show();
            } else {
                $('.date-range').hide();
            }
        });
    });
</script>
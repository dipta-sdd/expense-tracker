<?php
if (!defined('ABSPATH')) exit;
?>
<?php
$categories = expense_tracker_init()->getModule('categories')->getCategories();
// error_log(json_encode($categories));
?>
<div class="wrap">
    <h1><?php esc_html_e('Expense Reports', 'expense-tracker'); ?></h1>

    <!-- Filters -->
    <div class="postbox" style="padding: 15px; margin-top: 15px;">
        <form method="get">
            <input type="hidden" name="page" value="expense-reports">

            <div class="report-filters">
                <select name="period">
                    <option value="this_month"><?php esc_html_e('This Month', 'expense-tracker'); ?></option>
                    <option value="last_month"><?php esc_html_e('Last Month', 'expense-tracker'); ?></option>
                    <option value="this_year"><?php esc_html_e('This Year', 'expense-tracker'); ?></option>
                    <option value="custom"><?php esc_html_e('Custom Range', 'expense-tracker'); ?></option>
                </select>

                <div class="date-range " style=" display: inline-block; display: none;">
                    <input type="date" name="start_date">
                    <input type="date" name="end_date">
                </div>

                <select name="category">
                    <option value=""><?php esc_html_e('All Categories', 'expense-tracker'); ?></option>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?php echo esc_attr($category['id']); ?>"><?php echo esc_html($category['name']); ?></option>
                    <?php endforeach; ?>
                </select>

                <?php submit_button(esc_html__('Generate Report', 'expense-tracker'), 'primary', 'generate_report', false); ?>
            </div>
        </form>
    </div>

    <!-- Summary Cards -->
    <div class="report-summary"
        style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin: 20px 0;">
        <div class="postbox" style="padding: 15px;">
            <h3><?php esc_html_e('Total Expenses', 'expense-tracker'); ?></h3>
            <p class="total-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php esc_html_e('Average per Day', 'expense-tracker'); ?></h3>
            <p class="average-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php esc_html_e('Highest Expense', 'expense-tracker'); ?></h3>
            <p class="highest-amount">
                <!-- Populated dynamically -->
            </p>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php esc_html_e('Number of Transactions', 'expense-tracker'); ?></h3>
            <p class="transaction-count">
                <!-- Populated dynamically -->
            </p>
        </div>
    </div>

    <!-- Charts -->
    <div class="report-charts">
        <div class="postbox" style="padding: 15px; margin-bottom: 20px;">
            <h3><?php esc_html_e('Expenses Over Time', 'expense-tracker'); ?></h3>
            <canvas id="expenses-chart"></canvas>
        </div>

        <div class="postbox" style="padding: 15px;">
            <h3><?php esc_html_e('Expenses by Category', 'expense-tracker'); ?></h3>
            <canvas id="category-chart"></canvas>
        </div>
    </div>
</div>

<script>
    // jQuery(document).ready(function($) {
    //     $('select[name="period"]').on('change', function() {
    //         if ($(this).val() === 'custom') {
    //             $('.date-range').show();
    //         } else {
    //             $('.date-range').hide();
    //         }
    //     });
    // });
    jQuery(document).ready(function($) {

        let expensesChart = null;
        let categoryChart = null;
        let data = <?php echo wp_json_encode(expense_tracker_init()->getModule('report')->getExpenseReport(['period' => 'this_month'])); ?>;
        console.log(data);
        // Initialize charts
        function initCharts() {
            const expensesCtx = document
                .getElementById("expenses-chart")
                .getContext("2d");
            const categoryCtx = document
                .getElementById("category-chart")
                .getContext("2d");

            expensesChart = new Chart(expensesCtx, {
                type: "bar",
                data: {
                    labels: [],
                    datasets: [{
                        label: "Daily Expenses Amount",
                        data: [],
                        tension: 0.1,
                    }],
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                        },
                    },
                },
            });

            categoryChart = new Chart(categoryCtx, {
                type: "doughnut",
                data: {
                    labels: [],
                    datasets: [{
                        data: [],
                        backgroundColor: [
                            "#2271b1",
                            "#3498db",
                            "#9b59b6",
                            "#e74c3c",
                            "#f1c40f",
                            "#2ecc71",
                        ],
                    }, ],
                },
                options: {
                    responsive: true,
                },
            });
        }

        // Load report data
        function loadReportData() {
            const filters = {
                period: $('select[name="period"]').val(),
                start_date: $('input[name="start_date"]').val(),
                end_date: $('input[name="end_date"]').val(),
                category: $('select[name="category"]').val(),
            };
            // alert('test');
            $.ajax({
                url: '<?php echo esc_url(get_rest_url(null, 'expense-tracker/v1/reports/')); ?>',
                type: 'POST',
                data: filters,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-WP-Nonce', '<?php echo esc_attr(wp_create_nonce('wp_rest')); ?>');
                },
                success: function(response) {
                    updateCharts(response);
                    updateSummary(response);

                },
                error: function(error) {
                    console.error('Error loading report data:', error);
                }
            });
        }
        /*
         * Update charts with new data
         */
        function updateCharts(data) {
            // console.log(data);
            expensesChart.data.labels = data.timeline.labels;
            expensesChart.data.datasets[0].data = data.timeline.values;
            expensesChart.update();
            categoryChart.data.labels = data.categories.labels;
            categoryChart.data.datasets[0].data = data.categories.values;
            categoryChart.update();
        }

        function updateSummary(data) {
            $(".total-amount").text(data.summary.total);
            $(".average-amount").text(data.summary.average);
            $(".highest-amount").text(data.summary.highest);
            $(".transaction-count").text(data.summary.count);
        }

        $("form").on("submit", function(e) {
            e.preventDefault();
            loadReportData();
        });

        $('select[name="period"]').on("change", function() {
            if ($(this).val() === "custom") {
                $(".date-range").show();
            } else {
                $(".date-range").hide();
                loadReportData();
            }
        });


        initCharts();
        updateCharts(data);
        updateSummary(data);
    });
</script>
<?php

namespace ExpenseTracker\Controller;

class ReportHandler
{
    public function __construct()
    {
        add_action('wp_ajax_get_expense_report', [$this, 'getExpenseReport']);
    }

    public function getExpenseReport()
    {
        check_ajax_referer('expense_tracker_nonce', '_nonce');
        // error_log('getExpenseReport');
        $period = $_POST['period'] ?? 'this_month';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $category = $_POST['category'] ?? '';

        // Get date range based on period
        $dates = $this->getDateRange($period, $start_date, $end_date);

        // Get expenses data
        $expenses = expense_tracker_init()->getModule('expenses')->getExpenses([
            'start_date' => $dates['start'],
            'end_date' => $dates['end'],
            'category_id' => $category
        ]);

        // Prepare response data
        $response = [
            'timeline' => $this->prepareTimelineData($expenses, $dates['start'], $dates['end']),
            'categories' => $this->prepareCategoryData($expenses),
            'summary' => $this->prepareSummaryData($expenses)
        ];

        wp_send_json_success($response);
    }

    private function getDateRange($period, $start_date, $end_date)
    {
        $today = current_time('Y-m-d');

        switch ($period) {
            case 'this_month':
                return [
                    'start' => date('Y-m-01'),
                    'end' => date('Y-m-t')
                ];
            case 'last_month':
                return [
                    'start' => date('Y-m-01', strtotime('last month')),
                    'end' => date('Y-m-t', strtotime('last month'))
                ];
            case 'this_year':
                return [
                    'start' => date('Y-01-01'),
                    'end' => date('Y-12-31')
                ];
            case 'custom':
                return [
                    'start' => $start_date,
                    'end' => $end_date
                ];
            default:
                return [
                    'start' => date('Y-m-01'),
                    'end' => date('Y-m-t')
                ];
        }
    }

    private function prepareTimelineData($expenses, $start_date, $end_date)
    {
        $timeline = [
            'labels' => [],
            'values' => []
        ];

        // Create date range array
        $period = new \DatePeriod(
            new \DateTime($start_date),
            new \DateInterval('P1D'),
            new \DateTime($end_date)
        );

        // Initialize timeline with zeros
        foreach ($period as $date) {
            $timeline['labels'][] = $date->format('Y-m-d');
            $timeline['values'][] = 0;
        }

        // Fill in actual values
        foreach ($expenses as $expense) {
            $date = date('Y-m-d', strtotime($expense['date']));
            $index = array_search($date, $timeline['labels']);
            if ($index !== false) {
                $timeline['values'][$index] += floatval($expense['amount']);
            }
        }

        return $timeline;
    }

    private function prepareCategoryData($expenses)
    {
        $categories = [
            'labels' => [],
            'values' => []
        ];

        $category_totals = [];
        foreach ($expenses as $expense) {
            $category_name = $expense['category_name'];
            if (!isset($category_totals[$category_name])) {
                $category_totals[$category_name] = 0;
            }
            $category_totals[$category_name] += floatval($expense['amount']);
        }

        $categories['labels'] = array_keys($category_totals);
        $categories['values'] = array_values($category_totals);

        return $categories;
    }

    private function prepareSummaryData($expenses)
    {
        $total = 0;
        $highest = 0;
        $count = count($expenses);

        foreach ($expenses as $expense) {
            $amount = floatval($expense['amount']);
            $total += $amount;
            $highest = max($highest, $amount);
        }

        return [
            'total' => number_format($total, 2),
            'average' => $count > 0 ? number_format($total / $count, 2) : '0.00',
            'highest' => number_format($highest, 2),
            'count' => $count
        ];
    }
}
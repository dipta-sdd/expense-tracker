<?php

namespace ExpenseTracker\Controller;

use ExpenseTracker\Core\Request;

class ReportHandler
{
    public function __construct() {}

    /**
     * Handles the report request and returns the report data.
     *
     * @param Request $request The incoming request.
     * @return array The report data.
     */
    public function getReportController(Request $request)
    {
        $attrs = $request->all();
        return $this->getExpenseReport($attrs);
    }

    /**
     * Generates the expense report based on the provided attributes.
     *
     * @param array $attrs The attributes for the report.
     * @return array The generated report data.
     */
    public function getExpenseReport($attrs)
    {
        // check_ajax_referer('expense_tracker_nonce', '_nonce');
        $period = isset($attrs['period']) ? $attrs['period'] : 'this_month';
        $start_date = isset($attrs['start_date']) ? $attrs['start_date'] : '';
        $end_date = isset($attrs['end_date']) ? $attrs['end_date'] : '';
        $category = isset($attrs['category']) ? $attrs['category'] : '';

        // Get date range based on period
        $dates = $this->getDateRange($period, $start_date, $end_date);
        // error_log(json_encode($dates));
        $expenses = expense_tracker_init()->getModule('expenses')->getExpenses([
            'start_date' => $dates['start'],
            'end_date' => $dates['end'],
            'category_id' => $category
        ]);
        // error_log(json_encode($expenses));
        // Prepare response data
        $response = [
            'timeline' => $this->prepareTimelineData($expenses, $dates['start'], $dates['end']),
            'categories' => $this->prepareCategoryData($expenses),
            'summary' => $this->prepareSummaryData($expenses)
        ];

        return $response;
    }

    /**
     * Calculates the date range based on the selected period.
     *
     * @param string $period The selected period (this_month, last_month, this_year, custom).
     * @param string $start_date The custom start date.
     * @param string $end_date The custom end date.
     * @return array An array containing the start and end dates.
     */
    private function getDateRange($period, $start_date, $end_date)
    {
        $today = current_time('Y-m-d');

        switch ($period) {
            case 'this_month':
                return [
                    'start' => gmdate('Y-m-01'),
                    'end' => gmdate('Y-m-t')
                ];
            case 'last_month':
                return [
                    'start' => gmdate('Y-m-01', strtotime('last month')),
                    'end' => gmdate('Y-m-t', strtotime('last month'))
                ];
            case 'this_year':
                return [
                    'start' => gmdate('Y-01-01'),
                    'end' => gmdate('Y-12-31')
                ];
            case 'custom':
                return [
                    'start' => $start_date,
                    'end' => $end_date
                ];
            default:
                return [
                    'start' => gmdate('Y-m-01'),
                    'end' => gmdate('Y-m-t')
                ];
        }
    }

    /**
     * Prepares the timeline data for the report.
     *
     * @param array $expenses The expenses data.
     * @param string $start_date The start date of the report.
     * @param string $end_date The end date of the report.
     * @return array The prepared timeline data.
     */
    private function prepareTimelineData($expenses, $start_date, $end_date)
    {
        $timeline = [
            'labels' => [],
            'values' => [],
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
            $date = gmdate('Y-m-d', strtotime($expense['date']));
            $index = array_search($date, $timeline['labels']);
            if ($index !== false) {
                $timeline['values'][$index] += floatval($expense['amount']);
            }
        }

        return $timeline;
    }

    /**
     * Prepares the category data for the report.
     *
     * @param array $expenses The expenses data.
     * @return array The prepared category data.
     */
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

    /**
     * Prepares the summary data for the report.
     *
     * @param array $expenses The expenses data.
     * @return array The prepared summary data.
     */
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

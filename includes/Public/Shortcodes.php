<?php

namespace ExpenseTracker\Public;

class Shortcodes
{

    public function __construct()
    {
        add_shortcode('expense_tracker_summary', array($this, 'display_expense_summary'));
        // ... register other shortcodes
    }

    public function display_expense_summary($atts)
    {
        $atts = shortcode_atts(array(
            'type' => 'monthly', // Default to monthly summary
            'category' => '',    // Optional category filter
        ), $atts);

        // Retrieve expenses based on attributes
        $expenses = $this->get_expenses($atts['type'], $atts['category']);

        // Generate the summary output (HTML table or other format)
        $output = '<table>';
        // ... (loop through expenses and generate table rows)
        $output .= '</table>';

        return $output;
    }

    private function get_expenses($type, $category)
    {
        // Retrieve expenses from the database based on type and category
        // ... use the Expense class and Database class to fetch data
    }

    // ... other shortcode callback functions
}
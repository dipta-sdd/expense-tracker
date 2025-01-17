<?php

namespace ExpenseTracker\Core;

class Shortcodes
{

    public function __construct()
    {
        add_shortcode('expense_tracker_expenses', [$this, 'renderExpensesList']);
        add_shortcode('expense_submission_form', [$this, 'renderExpenseSubmissionForm']);
        add_action('wp_enqueue_scripts', [$this, 'enqueueScripts']);
    }

    public function enqueueScripts()
    {
        wp_enqueue_script('jquery');
        wp_enqueue_style('expense-tracker-styles', EXPENSE_TRACKER_URL . 'assets/css/expense-tracker-styles.css');
        wp_enqueue_script('expense-tracker-scripts', EXPENSE_TRACKER_URL . 'assets/js/expense-tracker-scripts.js', ['jquery'], false, true);
    }

    public function renderExpensesList($atts)
    {
        $filter = $atts['filter'] ?? true;

        $atts = shortcode_atts([
            'category_id' => '',
            'status'      => '',
            'sort_by'     => '',
        ], $atts, 'expense_tracker_expenses');

        $expenses = expense_tracker_init()->getModule('expenses')->getExpenses($atts);
        $categories = expense_tracker_init()->getModule('categories')->getCategories();
        ob_start();
        $view = expense_tracker_init()->getModule('view')->render('shortcode/list', compact('expenses', 'categories', 'atts', 'filter'));
        return ob_get_clean();
    }

    public function renderExpenseSubmissionForm($atts)
    {
        $atts = shortcode_atts([
            'category_id' => '',
            'hide_category' => false,
        ], $atts, 'expense_submission_form');

        $categories = expense_tracker_init()->getModule('categories')->getCategories();
        ob_start();
        $view = expense_tracker_init()->getModule('view')->render('shortcode/form', compact('categories', 'atts'));
        return ob_get_clean();
    }
}

// new Shortcodes();

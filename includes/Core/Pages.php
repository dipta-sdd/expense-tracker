<?php

namespace ExpenseTracker\Core;

class Pages
{
    public static function create_plugin_pages()
    {
        // Create Groups List Page
        if (!get_option('expense_tracker_groups_page_id')) {
            $groups_page = wp_insert_post([
                'post_title' => 'My Groups',
                'post_content' => '[expense_tracker_groups]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            update_option('expense_tracker_groups_page_id', $groups_page);
        }

        // Create Single Group Page
        if (!get_option('expense_tracker_single_group_page_id')) {
            $single_group_page = wp_insert_post([
                'post_title' => 'Group Details',
                'post_content' => '[expense_tracker_group]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            update_option('expense_tracker_single_group_page_id', $single_group_page);
        }

        // Create Personal Expenses Page
        if (!get_option('expense_tracker_expenses_page_id')) {
            $expenses_page = wp_insert_post([
                'post_title' => 'My Expenses',
                'post_content' => '[expense_tracker_personal_expenses]',
                'post_status' => 'publish',
                'post_type' => 'page'
            ]);
            update_option('expense_tracker_expenses_page_id', $expenses_page);
        }
    }

    public static function get_page_url($page_type)
    {
        switch ($page_type) {
            case 'groups':
                return get_permalink(get_option('expense_tracker_groups_page_id'));
            case 'single_group':
                return get_permalink(get_option('expense_tracker_single_group_page_id'));
            case 'expenses':
                return get_permalink(get_option('expense_tracker_expenses_page_id'));
            default:
                return home_url();
        }
    }
}
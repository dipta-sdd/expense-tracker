<?php

namespace ExpenseTracker\Core;

class View
{
    private $template_path;

    public function __construct()
    {
        $this->template_path = EXPENSE_TRACKER_PATH . 'views/';
    }

    public function render($template, $data = [])
    {
        $template_file = $this->template_path . $template . '.php';

        if (!file_exists($template_file)) {
            wp_die(sprintf('Template file %s not found', $template_file));
        }

        // Extract data to make it available in template
        if (!empty($data)) {
            extract($data);
        }

        include $template_file;
    }
}

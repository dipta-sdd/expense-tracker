<?php

namespace ExpenseTracker\Controller;

use ExpenseTracker\Core\Request;

class CategoryController
{
    private $categories;

    public function __construct()
    {
        $this->categories = expense_tracker_init()->getModule('categories');
    }

    public function index(Request $request)
    {
        $categories = $this->categories->getCategories();
        return rest_ensure_response($categories);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $result = $this->categories->createCategory($data);

        if (is_wp_error($result)) {
            return $result;
        }

        $category = $this->categories->getCategory($result);
        return rest_ensure_response($category);
    }

    public function show(Request $request, $id)
    {
        $category = $this->categories->getCategory($id);

        if (!$category) {
            return new \WP_Error('not_found', __('Category not found.', 'expense-tracker'), ['status' => 404]);
        }

        return rest_ensure_response($category);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $result = $this->categories->updateCategory($id, $data);

        if (is_wp_error($result)) {
            return $result;
        }

        $category = $this->categories->getCategory($id);
        return rest_ensure_response($category);
    }

    public function destroy(Request $request, $id)
    {
        $result = $this->categories->deleteCategory($id);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response(['message' => __('Category deleted successfully.', 'expense-tracker')]);
    }
}

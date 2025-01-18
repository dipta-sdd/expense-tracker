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

    /**
     *  Get all categories
     *
     * @return \WP_REST_Response
     */
    public function index()
    {
        $categories = $this->categories->getCategories();
        return rest_ensure_response($categories);
    }

    /**
     * Store a new category
     *
     * @param Request $request
     * @return \WP_Error|\WP_REST_Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        if (!$request->isValid()) {
            return new \WP_Error('validation_error', $request->getErrors(), ['status' => 400]);
        }
        $data = $request->all();
        $result = $this->categories->createCategory($data);

        if (is_wp_error($result)) {
            return $result;
        }

        $category = $this->categories->getCategory($result);
        return rest_ensure_response($category);
    }

    /**
     * Get a single category
     *
     * @param int $id
     * @return \WP_Error|\WP_REST_Response
     */
    public function show($id)
    {
        $category = $this->categories->getCategory($id);

        if (!$category) {
            return new \WP_Error('not_found', __('Category not found.', 'expense-tracker'), ['status' => 404]);
        }

        return rest_ensure_response($category);
    }

    /**
     * Update a category
     *
     * @param Request $request
     * @param int $id
     * @return \WP_Error|\WP_REST_Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
        ]);
        if (!$request->isValid()) {
            return new \WP_Error('validation_error', $request->getErrors(), ['status' => 400]);
        }
        $data = $request->all();
        $result = $this->categories->updateCategory($id, $data);

        if (is_wp_error($result)) {
            return $result;
        }

        $category = $this->categories->getCategory($id);
        return rest_ensure_response($category);
    }
    /**
     * Delete a category
     *
     * @param int $id
     * @return \WP_Error|\WP_REST_Response
     */
    public function destroy($id)
    {
        $result = $this->categories->deleteCategory($id);

        if (is_wp_error($result)) {
            return $result;
        }

        return rest_ensure_response(['message' => __('Category deleted successfully.', 'expense-tracker')]);
    }
}

<?php

namespace ExpenseTracker\Controller;

use WP_REST_Request;
use WP_REST_Response;

class GroupController
{
    public static function create_group(WP_REST_Request $request)
    {
        $params = $request->get_json_params();

        // Validate required fields
        if (empty($params['name'])) {
            return new WP_REST_Response([
                'message' => 'Group name is required'
            ], 400);
        }

        // Create group logic here
        return new WP_REST_Response([
            'message' => 'Group created successfully',
            'data' => $params
        ], 201);
    }
}

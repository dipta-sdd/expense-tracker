<?php

namespace ExpenseTracker\Controller;

use NinjaDB\BaseModel;
use WP_REST_Request;
use WP_REST_Response;
use ExpenseTracker\Core\Request;

class GroupController
{

    public function __construct() {}

    public static function create_group(WP_REST_Request $request)
    {
        $request = new Request($request->get_json_params());
        $request->validate([
            'description' => 'required|string|min:3|max:255',
            'name' => 'required|string|min:3|max:255',
            'budget' => 'nullable|numeric|min:0',
        ]);
        if (!$request->isValid()) {
            return new WP_REST_Response([
                'message' => 'Validation failed',
                'errors' => $request->getErrors()
            ], 400);
        }
        $params = $request->all();
        $params['admin_id'] = get_current_user_id();

        $db = new BaseModel('expense_tracker_groups');
        $group = $db->insert($params);

        $db = new BaseModel('expense_tracker_groups as g');
        $group = $db->select(' g.* , admin.display_name as admin_name')
            ->join('users as admin', 'admin_id', 'ID')
            ->where('group_id', $group)->first();


        return new WP_REST_Response([
            'message' => 'Group created successfully',
            'data' => $group
        ], 201);
    }

    public static function delete_group($request)
    {
        $db = new BaseModel('expense_tracker_groups');
        $db->where('group_id', $request['id'])->delete();
        return new WP_REST_Response([
            'message' => 'Group deleted successfully',
            'data' => $request['id']
        ], 200);
    }

    public static function update_group(WP_REST_Request $request)
    {
        $group_id = $request->get_param('id');
        $user_id = get_current_user_id();

        // Check if user is admin of the group
        $db = new BaseModel('expense_tracker_groups');
        $group = $db->where('group_id', $group_id)
            ->where('admin_id', $user_id)
            ->first();

        if (!$group) {
            return new WP_REST_Response([
                'message' => 'Group not found or permission denied'
            ], 404);
        }

        $request = new Request($request->get_json_params());
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'description' => 'required|string|min:3',
            'budget' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:Active,Inactive'
        ]);

        if (!$request->isValid()) {
            return new WP_REST_Response([
                'message' => 'Validation failed',
                'errors' => $request->getErrors()
            ], 400);
        }

        $params = $request->all();
        $db->where('group_id', $group_id)->update($params);

        // Get updated group data
        $updated_group = $db->select([
            'g.*',
            'admin.display_name as admin_name'
        ])
            ->table('expense_tracker_groups as g')
            ->join('users as admin', 'g.admin_id', '=', 'admin.ID')
            ->where('g.group_id', $group_id)
            ->first();

        return new WP_REST_Response([
            'message' => 'Group updated successfully',
            'group' => $updated_group
        ]);
    }
}
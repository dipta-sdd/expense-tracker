<?php

namespace ExpenseTracker\Modules;

use NinjaDB\BaseModel;

class Groups
{
    private $db;
    protected $table = 'expense_tracker_groups';

    public function __construct() {}

    public function getAllGroups($user_id) {}

    public function getGroupById($group_id, $user_id) {}

    public function createGroup($data) {}

    public function updateGroup($group_id, $data) {}

    public function deleteGroup($group_id, $admin_id) {}
}
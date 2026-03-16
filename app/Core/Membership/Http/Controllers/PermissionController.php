<?php

namespace App\Core\Membership\Http\Controllers;

use App\Core\Membership\Models\Permission;
use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    public function index()
    {
        return Permission::all();
    }
}

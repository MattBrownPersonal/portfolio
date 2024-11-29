<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\Role
     */
    public function index()
    {
        return Role::all()->take(2);
    }
}

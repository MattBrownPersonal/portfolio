<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\User
     */
    public function index()
    {
        return User::orderBy('firstname')->get();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $user->email_notify_on_new_order = $request->value;
        $user->save();
    }
}

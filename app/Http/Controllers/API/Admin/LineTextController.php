<?php

namespace App\Http\Controllers\API\Admin;

use App\Http\Controllers\Controller;
use App\Models\LineText;

class LineTextController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return App\Models\LineText
     */
    public function index()
    {
        return LineText::all();
    }
}

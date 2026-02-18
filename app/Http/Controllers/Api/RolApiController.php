<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Rol;

class RolApiController extends Controller
{
    public function index()
    {
        $roles = Rol::all();
        return response()->json($roles);
    }
}

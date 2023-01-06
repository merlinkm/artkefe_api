<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function getAllRoles()
    {
        return Role::get();
    }
}

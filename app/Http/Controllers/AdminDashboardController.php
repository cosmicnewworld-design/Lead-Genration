<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.dashboard', compact('users'));
    }
}

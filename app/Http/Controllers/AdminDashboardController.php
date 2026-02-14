<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $users = User::all();
        $leads = Lead::with('business')->latest()->paginate(10);
        return view('admin.dashboard', compact('users', 'leads'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AnalyticsController extends Controller
{
    public function index() {
        // Calculate total held by all users
        $totalHeld = 0;

        $usersBalance = User::all();
        $users = User::paginate(25);

        foreach ($usersBalance as $user) {
            $totalHeld += $user->balance();
        }
        return view('admin.analytics', compact('totalHeld', 'users'));
    }
}

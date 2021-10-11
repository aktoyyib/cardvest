<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        $users = User::paginate(25);
        return view('admin.users.index', compact('users'));
    }

    public function search()
    {
        $param = request()->query('search');
        $users = User::query();
        $users = $users->where('email', 'LIKE', "%{$param}%")
                ->where('username', 'LIKE', "%{$param}%")
                ->simplePaginate(25);

        return view('admin.users.search', compact('users'));
    }

    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    public function banUser(Request $request, User $user)
    {
        $request->validate([
            'terms' => ['required', Rule::in(['permanent', 'temporary'])],
            'duration' => ['nullable', 'numeric', 'integer'],
        ]);

        if ($request->terms == 'temporary') {
            $user->ban($request->duration);
        } else {
            $user->ban();
        }

        return back()->with('success', $user->username.' has been banned '.$user->banTerms());
    }

    public function liftBan(User $user)
    {
        $user->liftBan();

        return back()->with('success', $user->username.' account has been re-activated.');
    }
}
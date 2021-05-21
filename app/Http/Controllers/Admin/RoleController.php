<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use \Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index($searched_users = null, $param = null)
    {
        $users = User::role('admin')->simplePaginate(10);
        return view('admin.roles.index', compact('users', 'searched_users', 'param'));
    }

    public function search()
    {
        $param = request()->query('user');
        $users = User::query();
        $users = $users->where('username', 'LIKE', "%{$param}%")
                    ->orWhere('email', 'LIKE', "%{$param}%")
                    ->get();
        return $this->index($users, $param);
    }

    public function store(User $user, $role = 'admin') {
        $role = Role::where('name',$role)->first();
        if ($user->hasRole(['admin'])) { //hasAnyRoles([])
            return redirect()->route('roles.index')->with('info', 'User is already an admin');
        }
        $user->assignRole($role);
        return redirect()->route('roles.index')->with('success', 'User made admin successfully!');
    }

    public function destroy(User $user, $role = 'admin') {
        $role = Role::where('name',$role)->first();
        if (!$user->hasRole(['admin'])) {
            return redirect()->route('roles.index')->with('info', 'Invalid action');
        }
        if ($user->id == auth()->id()) return redirect()->route('roles.index');
        $user->removeRole('admin');
        return redirect()->route('roles.index')->with('success', 'User removed from the role successfully!');
    }
}
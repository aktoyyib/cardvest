<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Rules\Password;

class UserController extends Controller
{
    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'string', 'min:11', 'max:15'],
        ]);
        
        $user = auth()->user();
        $user->update($validated);

        return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', new Password, 'confirmed'],
            'password_confirmation' => ['required', 'string', new Password],
        ]);

        $user = auth()->user();

        if (!isset($input['current_password']) || ! Hash::check($request->current_password, $user->password)) {
            abort(400, __('The provided password does not match your current password.'));
        }

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
        
        return new UserResource($user);
    }
}

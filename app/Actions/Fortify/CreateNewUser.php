<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

use DB;
use App\Models\Wallet;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255'],
            'phonenumber' => ['required', 'string', 'min:11', 'max:15'],
            'terms' => ['required'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ])->validate();

        DB::beginTransaction();

        try {
            $user = User::create([
                'username' => $input['username'],
                'phonenumber' => $input['phonenumber'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
            ]);
    
            $wallet = Wallet::create([]);
    
            $wallet->user()->associate($user);
            $wallet->save();
        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('error', 'Could not process registration, please try again!');
        }

        DB::commit();

        return $user;
    }
}
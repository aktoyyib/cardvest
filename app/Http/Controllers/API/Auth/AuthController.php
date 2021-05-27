<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Resources\User\UserResource;

use DB;
use App\Models\Wallet;
use App\Jobs\SendWelcomeMail;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Rules\Password;
use App\Services\API\TransactionService;

class AuthController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
            'phonenumber' => ['required', 'string', 'min:11', 'max:15'],
            'terms' => ['required'],
            'referrer' => ['nullable', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password],
        ]);
        
        // Referral System
        $referrer = null;
        
        // Check if there is this user was referred
        if ($request->has('referrer')) {
            $referrer = User::whereUsername($request->referrer)->first();
        }
        $referrer_id = !is_null($referrer) ? $referrer->id : null;

        DB::beginTransaction();

        try {
            
            $user = User::create([
                'username' => $request->username,
                'phonenumber' => $request->phonenumber,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'referrer_id' => $referrer_id,
            ]);
    
            $wallet = Wallet::create([]);
    
            $wallet->user()->associate($user);
            $wallet->save();

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        // Dispatch Welcome Email
        // SendWelcomeMail::dispatchAfterResponse($user);
        // $this->transactionService->addToAudienceList($user);

        return $user;
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'password' => 'required|min:8',
            'device_name' => 'required|max:255',
        ]);
    
        $user = \App\Models\User::where('email', $request->email)->firstOrFail();
    
        $user->tokens()->delete();
    
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }
    
        return $user->createToken($request->device_name)->plainTextToken;
    }

    public function logout(Request $request)
    {
        $user = auth()->user();
        $user->tokens()->delete();
        return response()->json(['message' => 'User logged out.'], 200);
    }

    public function user()
    {
        return new UserResource(auth()->user());
    }
}

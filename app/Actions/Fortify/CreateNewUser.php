<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

use App\Services\TransactionService;

use App\Models\Wallet;
use App\Jobs\SendWelcomeMail;
use Illuminate\Support\Facades\DB;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'username' => ['required', 'string', 'max:255', Rule::unique(User::class)],
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

        // Referral System
        $referrer = null;

        // Check if there is this user was referred
        if (request()->hasCookie('cardvest_referrer')) {
            $referrer = User::whereUsername(request()->cookie('cardvest_referrer'))->first();
            // cookie()->queue(cookie()->forget('cardvest_referrer'));

        }
        $referrer_id = !is_null($referrer) ? $referrer->id : null;

        DB::beginTransaction();

        try {

            $user = User::create([
                'username' => $input['username'],
                'phonenumber' => $input['phonenumber'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                'referrer_id' => $referrer_id,
            ]);

            // $nairWallet->user()->associate($user);
            // $nairWallet->save();

            // $cedisWallet->user()->associate($user);
            // $cedisWallet->save();

            // OR
            $user->fiat_wallets()->createMany([
                [
                    'currency' => 'NGN',
                    'name' => 'Naira',
                    'isDefault' => true // Making naira the default wallet
                ],
                [
                    'currency' => 'GHS',
                    'name' => 'Cedis'
                ]
            ]);

        } catch (\Throwable $e) {
            DB::rollback();
            return null;
        }

        DB::commit();

        // Delete the referral cookie only if the registration was successful
        if (request()->hasCookie('cardvest_referrer')) {
            cookie()->queue(cookie()->forget('cardvest_referrer'));

            //  Attach the referrer to the user
            $user->referrer()->associate($referrer);
            $user->save();
        }

        // Dispatch Welcome Email
        // SendWelcomeMail::dispatchAfterResponse($user);
        $this->transactionService->addToAudienceList($user);

        return $user;
    }
}

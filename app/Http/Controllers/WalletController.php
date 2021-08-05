<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Bank;
use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use Illuminate\Support\Facades\Http;

class WalletController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'banknumber' => 'required|string|min:10|max:10',
            'bankname' => 'required|string|max:255',
            'accountname' => 'required|string|max:255',
        ]);

        $bank = json_decode($request->bankname);
        $request->merge(['bankname' => $bank->name, 'code' => $bank->code]);
        // return $request;
        
        // Get the users wallet
        $wallet = auth()->user()->wallet;
        // Create a Bank Account
        $bank = Bank::create($request->all());
        // Attach bank to wallet
        $wallet->bank_accounts()->save($bank);
        
        return back()->with('success', 'Bank account successfully added!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wallet  $wallet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Bank $bank)
    {
        $bank->lightDelete();
        return back()->with('success', 'Bank account successfully removed');
    }

    public function banks()
    {
        $banks = Flutterwave::banks()->nigeria();
        
        return response($banks);
    }

    public function verify(Request $request)
    {
        $request->validate([
            'banknumber' => 'required|min:10',
            'bankname' => 'required',
        ]);
        
        $response = Http::withToken(config('flutterwave.secretKey'))
                        ->post('https://api.flutterwave.com/v3/accounts/resolve', [
                            'account_number' => $request->banknumber,
                            'account_bank' => $request->bankname
                        ]);
        
        return response($response);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Wallet;
use App\Models\Bank;
use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use Illuminate\Support\Facades\Http;
use App\Payment\PaymentGateway;

class WalletController extends Controller
{
    public function show(string $currency)
    {
        // Fetcht the users ($currency) wallet
        $wallet = auth()->user()
            ->fiat_wallets()->currency($currency)->first();

        if (is_null($wallet)) {
            return redirect()->route('home');
        }

        $user = auth()->user();
        $withdrawals = $user->withdrawals()->currency($currency)->desc()->simplePaginate(4);
        $banks = $wallet->bank_accounts()->active()->get();
        // return $wallet;
        return view('wallet', compact('user', 'wallet', 'banks', 'withdrawals'));
    }
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
            'currency' => 'required|string'
        ]);

        $bank = json_decode($request->bankname);
        $request->merge(['bankname' => $bank->name, 'code' => $bank->code]);
        // return $request;

        // Get the users wallet
        $wallet = auth()->user()->fiat_wallets()->currency($request->currency)->first();
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

    public function banks(Request $request)
    {
        $request->validate([
            'currency' => 'required|string'
        ]);

        $banks = PaymentGateway::currency(request()->currency)->fetchBanks();

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

    public function setDefaultCurrency(Request $request)
    {
        $request->validate([
            'currency' => 'required|string'
        ]);

        $currency = $request->currency;

        // Logic to change default currency
        $wallet = auth()->user()->fiat_wallets()->where('currency', $currency)->first();
        $currentDefaultWallet = auth()->user()->wallet;

        $wallet->makeDefault();
        $currentDefaultWallet->removeDefault();

        return back()->with('success', 'Your default currency has been set to '.$request->currency.' successfully!');
    }

}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\Bank\BankResource;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class WalletController extends Controller
{
    public function addBankAccounts(Request $request)
    {
        $request->validate([
            'banknumber' => 'required|string|min:10|max:10',
            'bankname' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'accountname' => 'required|string|max:255',
        ]);

        // Get the users wallet
        $wallet = auth()->user()->wallet;
        // Create a Bank Account
        $bank = Bank::create($request->all());
        // Attach bank to wallet
        $wallet->bank_accounts()->save($bank);

        return response()->json([
            'message' => 'Bank account successfully added!',
            'status' => 'success'
        ]);
    }

    public function destroy(Bank $bank)
    {
        if (!$bank->withdrawals->isEmpty() || !$bank->transactions->isEmpty()) {
            return abort(422, 'You cannot remove bank account as it is currently in use.');
        }
        $bank->delete();
        return response()->json([
            'messsage' => 'Bank account successfully removed',
            'status' => 'success'
        ]);
    }

    public function banks()
    {
        $banks = Flutterwave::banks()->nigeria();

        return response()->json(['data' => $banks]);
    }

    // GIving me some weird data
    // "data": {
    //     "cookies": {},
    //     "transferStats": {}
    // }
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
            ])->json();

        return response()->json($response);
    }

    public function getBankAccounts()
    {
        return BankResource::collection(auth()->user()->wallet->bank_accounts);
    }
}

<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Withdrawal;
use App\Services\API\TransactionService;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

use App\Http\Resources\Withdrawal\WithdrawalResource;
use App\Http\Resources\Withdrawal\WithdrawalCollection;

class WithdrawalController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        return new WithdrawalCollection(auth()->user()->withdrawals);
    }

    public function get(Withdrawal $withdrawal)
    {
        return new WithdrawalResource($withdrawal);
    }

    public function store(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'bank' => 'required'
        ]);

        $user = auth()->user();
        if (!$user->isSufficient($request->amount)) {
            return response()->json(['message' => 'Your withdrawal wallet is insufficient!'], 400);
        }

        $withdrawal = $this->transactionService->makeWithdrawal($request);

        if ($withdrawal->status === 'pending') {
            return back()->with('info', 'You withdrawal request is being processed!');
        }
        // Successful
        return back()->with('success', 'You withdrawal request has been processed successfully!');
    }
}

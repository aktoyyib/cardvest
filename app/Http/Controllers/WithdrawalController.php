<?php

namespace App\Http\Controllers;

use App\Models\Withdrawal;
use Illuminate\Http\Request;
use App\Services\TransactionService;

class WithdrawalController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
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
            'amount' => 'required|numeric|min:0',
            'bank' => 'required',
            'currency' => 'required|string'
        ]);

        $user = auth()->user();

        if (!$user->isSufficient($request->amount * 100, $request->currency)) {
            return back()->with('error', 'Your withdrawal wallet is insufficient!');
        }

        $withdrawal = $this->transactionService->makeWithdrawal($request);

        if ($withdrawal->status === 'pending') {
            return back()->with('info', 'You withdrawal request is being processed!');
        }
        // Successful
        return back()->with('success', 'You withdrawal request has been processed successfully!');
    }

}

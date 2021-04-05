<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;
use App\Services\TransactionService;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $transactions = Transaction::desc()->paginate(25);
        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // Return View for transferring
        $user = auth()->user();
        $transactions = Transaction::hasRecipient()->admin()->desc()->paginate(10);
        return view('admin.transactions.create', compact('user', 'transactions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!isset($request->role)) {
            return back()->with('error', 'Unauthorized access!');
        }

        $request->validate([
            'email' => ['required', 'string',  'max:255'],
            'amount' => ['required', 'numeric', 'min:1.00'],
        ]);

        $amount = $request->amount;
        $user = auth()->user();
        $recipient = User::search($request->username)->first();

        if (!$user->isSufficient($amount) && $request->role == 'funder') {
            return back()->with('error', 'Your wallet balance is insufficient for this transaction.');
        }
        
        if (!$recipient || $request->email === $user->email) {
            return back()->with('error', 'There is no such user with the email: ' .$request->email);
        }

        $this->transactionService->makeTransfer($request, $user, $recipient);

        $amount = to_naira($amount*100);
        return back()->with('success', 'You have successfully transferred ($) ' .$amount. ' to ' .$request->email. '\'s wallet.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'admin_comment' => 'nullable|string',
            'status' => 'required|in:pending,rejected,succeed',
            'payment_status' => 'nullable|in:failed,pending,succeed'
        ]);


        $transaction->update($request->all());

        return back()->with('info', 'Transaction updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
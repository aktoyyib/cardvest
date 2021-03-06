<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Card;
use App\Models\Transaction;
use App\Services\TransactionService;
use Spatie\Activitylog\Models\Activity;

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
    public function index($searched_transactions = null, $param = null)
    {
//        The searched_transaction and param comes from the search function of this controller
//        This is to enable search result to be displayed on the normal
        $transactions = Transaction::desc()->paginate(25);

        // Transaction Types - Sell | Buy | Payout
        // Transactions should actually show only card sales or purchase (Sell - Pending / Success / Rejected |  Buy - Successful payment)
        // Payouts should be moved to its own separate page
        return view('admin.transactions.index', compact('transactions', 'searched_transactions', 'param'));
    }

    public function search()
    {
        $param = request()->query('search');
        $transactions = Transaction::query();
        $transactions = $transactions->where('reference', 'LIKE', "%{$param}%")
            ->get();

        return $this->index($transactions, $param);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
//        Log payout activity

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
//        Log payout sending activity

        if (!isset($request->role)) {
            return back()->with('error', 'Unauthorized access!');
        }

        $request->validate([
            'email' => ['required', 'string',  'max:255'],
            'amount' => ['required', 'numeric', 'min:1.00'],
            'currency' => ['required', 'string']
        ]);

        $amount = $request->amount;
        $user = auth()->user();
        $recipient = User::search($request->email)->first();

        if (!$user->isSufficient($amount, $request->currency) && $request->role == 'funder') {
            return back()->with('error', 'Your wallet balance is insufficient for this transaction.');
        }
        // dd($recipient);
        if (!$recipient || $request->email === $user->email) {
            return back()->with('error', 'Could not be completed');
        }

        $this->transactionService->makeTransfer($request, $user, $recipient);

        $amount = to_naira($amount*100);
        return back()->with('success', 'You have successfully transferred ('.$request->currency.') ' .$amount. ' to ' .$request->email. '\'s wallet.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
//        Log payout activity - viewing a specific transaction
        activity('admin.transactions')
            ->performedOn($transaction)
            ->log('viewed');

        return view('admin.transactions.show', compact('transaction'));
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
        // Check if the payment has been processed already
        if ($transaction->status != 'pending') {
//            Log Payout activity - Transaction already approved
            $action = $request->status === 'rejected' ? 'decline' : 'approve';
            $action = $request->status === 'succeed' ? 'approve' : 'processing';
            activity('admin.transactions')
                ->performedOn($transaction)
                ->withProperties([
                    'action' => $action
                ])
                ->log('to_update');

            return back()->with('info', 'Transaction has been processed already.');
        }

        $p_s = array('failed', 'pending', 'succeed');
        $_s = array('rejected', 'pending', 'succeed');

        // Get the value before adding other fields to the request array
        $userIsToBePaid = $transaction->type == 'sell' && $request->has('status') && $request->status == 'succeed';

        if (!in_array($request->status, $_s)) {
            $request->merge(['status' => $transaction->status]);
        }

        if (!in_array($request->payment_status, $p_s)) {
            $request->merge(['payment_status' => $transaction->payment_status]);
        }

        $request->validate([
            'admin_comment' => 'nullable|string',
            'status' => 'nullable|in:pending,rejected,succeed',
            'payment_status' => 'nullable|in:failed,pending,succeed',
            'amount' => 'nullable|numeric|min:0',
        ]);

        //  Check if the card_id is valid
        if ($request->has('card_id') && is_null(Card::find($request->card_id))) {
            return back()->with('warning','Please select the valid card to continue.');
        }

        if (!is_null($request->amount)) {
            $amount = $request->amount * 100;
        } else {
            $amount = $transaction->amount;
        }

        try {
            $transaction->update($request->merge(['amount' => $amount])->all());
        } catch(\Throwable $e) {
            return back()->with('error', 'Transaction could not be updated. Check that the necessary inputs are valid and try again.');
        }

        if ($userIsToBePaid) {
            // Credit a user with a payout
            $recipient = $transaction->user;
            $this->transactionService->makeTransfer($request->merge([
                'role' => 'admin',
                'admin_comment' => 'Card Payout',
                'amount' => $amount/100,
                'currency' => $transaction->currency
            ]), auth()->user(), $recipient);
        }

        // Settle the referrer of the owner of the transaction
        // Iff: has referrer AND the referrer is not settled (= 0)
        if ($request->status == 'succeed') {
            $the_transaction_user = $transaction->user;
            if ($the_transaction_user->referrer != null && !$the_transaction_user->referrerIsSettled()) {
                // Get the referrer
                $referrer = $the_transaction_user->referrer;
                // Settle the referrer
                $this->transactionService->makeReferral($the_transaction_user, $referrer);
            }
        }

        // Send notification
        $this->transactionService->notifyUser($transaction);

        return back()->with('info', 'Transaction updated successfully!');
    }

    public function adminCommentImageUpload(Request $request, Transaction $transaction)
    {
        $fileName = $this->transactionService->uploadImage($request, 'admin-comment-images');
        $transaction->update([
            'admin_images' => $fileName
        ]);
    }

}

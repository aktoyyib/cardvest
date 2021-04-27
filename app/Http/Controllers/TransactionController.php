<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Card;
use App\Services\TransactionService;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

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
        $transactions = auth()->user()->transactions()->cardSaleOrPurchase()->desc()->paginate(10);
        
        return view('transactions', compact('transactions'));
    }

    /**
     * Show the card trading form
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $categories = Category::all();
        $cardsToBuy = Card::where('type', 'buy')->get();
        $banks = auth()->user()->wallet->bank_accounts->all();
        return view('trade', compact('categories', 'cardsToBuy', 'banks'));
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
            'card_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            // 'images*' => 'nullable|image|mimes:jpg,png,jpeg,pdf|max:2048',
            'to_bank' => 'nullable',
            'bank' => 'nullable|numeric',
            'comment' => 'nullable|string'
        ]);

        return $this->transactionService->sellCard($request);
    }

    /**
     * Buy card
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function buy(Request $request)
    {
        $request->validate([
            'card_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'comment' => 'nullable|string'
        ]);

        return $this->transactionService->buyCard($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function show(Transaction $transaction)
    {
        return view('single-transaction', compact('transaction'));
    }
    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback()
    {
        return $this->transactionService->callback();
    }

    /**
     * Receives Flutterwave webhook
     * @return void
     */
    public function webhook(Request $request)
    {
        $this->transactionService->webhook($request);
    }

    public function upload(Request $request)
    {
        return $this->transactionService->uploadImage($request);
    }
}
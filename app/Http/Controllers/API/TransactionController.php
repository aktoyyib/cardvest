<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Transaction;
use App\Models\Category;
use App\Models\Card;
use App\Models\Bank;
use App\Services\API\TransactionService;
use Illuminate\Support\Str;

use App\Http\Resources\Transaction\TransactionResource;

class TransactionController extends Controller
{
    protected $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $transactions = auth()->user()->transactions()->cardSaleOrPurchase()->desc()->paginate(10);

        return TransactionResource::collection($transactions);
    }

    public function payouts()
    {
        $payouts = Transaction::completed()->desc()->payouts()->mine()->get();

        return TransactionResource::collection($payouts);
    }

    // Done ✔
    public function sell(Request $request)
    {
        $request->validate([
            'card_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'images' => 'required',
            'images.*' => 'mimes:jpeg,jpg,png|max:2048',
            'to_bank' => 'nullable',
            'bank' => 'nullable|numeric',
            'comment' => 'nullable|string',
        ], [
            'card_id.numeric' => 'A valid gift card must be selected',
            'card_id.required' => 'You must select a gift card to continue',
            'images.required' => 'You must upload the shot of the gift card',
            'images.*.mimes' => 'Uploaded card can only be of the types jpeg, jpg or png'
        ]);

        //  Check if the card_id is valid
        if (is_null(Card::find($request->card_id))) {
            abort(400, 'Gift card is invalid');
        }

        //  Check if bank is valid 
        if (isset($request->to_bank) && is_null(Bank::find($request->bank))) {
            abort(400, 'Bank must be valid if supplied');
        }

        $response = $this->transactionService->sellCard($request);
        
        return response()->json([
            'status' => 'success',
            'message' => $response['message'],
            'data' => $response['data']
        ]);
    }

    // Done ✔
    public function buy(Request $request)
    {
        $request->validate([
            'card_id' => 'required|numeric',
            'amount' => 'required|numeric|min:0',
            'comment' => 'nullable|string'
        ], [
            'card_id.numeric' => 'A valid gift card must be selected',
            'card_id.required' => 'You must select a gift card to continue'
        ]);

        //  Check if the card_id is valid
        if (is_null(Card::find($request->card_id))) {
            abort(400, 'Gift card is invalid');
        }

        $paymentLink = $this->transactionService->buyCard($request);

        return response()->json([
            'status' => 'success',
            'message' => 'Proceed to make payment',
            'data' => ['paymentLink' => $paymentLink]
        ]);
    }

    /**
     * Get the specified resource.
     *
     * @param  \App\Models\Transaction  $transaction
     * @return \Illuminate\Http\Response
     */
    public function get(Transaction $transaction)
    {
        return new TransactionResource($transaction);
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
        $request->validate([
            'file' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $filename = $this->transactionService->uploadImage($request);

        return response()->json([
            'message' => 'Image uploaded successfully',
            'status' => 'success',
            'data' => [
                'filename' => $filename,
            ],
        ]);
    }
}

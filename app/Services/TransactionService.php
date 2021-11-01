<?php


namespace App\Services;


use App\Models\Card;
use App\Models\Category;
use App\Models\Bank;
use App\Models\Withdrawal;
use App\Models\Transaction;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
use Illuminate\Support\Str;
use KingFlamez\Rave\Facades\Rave as Flutterwave;
use Illuminate\Support\Facades\Log;

use App\Notifications\Order;
use App\Notifications\OrderProcessed;
use Illuminate\Support\Facades\Notification;
use App\Payment\PaymentGateway;


class TransactionService
{
    protected $role = 'user';
    const REFERRAL_BONUS = 50000; //kobo
    // const REGISTRATION_BONUS = 3000;

    // ALl amounts must be converted to kobo before processing
    // Likewise all values must be converted to naira before being displayed on the pages.
    // Convert to naira using a readily declared function to_naira that takes the value in kobo as arguement
    // The function can be found in App\Helpers\functions.php

    public function makeReferral(User $user, User $recipient) {
        $bonus_amount = self::REFERRAL_BONUS;

        // Generate transaction reference code
        $ref = 'REFERRAL_BONUS';

        // Get the users default currency
        $currency = auth()->user()->currency();

        // Convert the referral bonus to the respective currency
        $bonus_amount = convert_to($bonus_amount, $currency);


        // Credit the referrer
        $recipient->credit($bonus_amount, $currency);

        Transaction::create([
            'user_id'=> $recipient->id,
            'type'=>'payout',
            'balance' => $recipient->balance(),
            'reference' => $ref,
            'status' => 'succeed',
            'amount' => $bonus_amount,
            'unit' => 0,
            'currency' => $currency
        ]);

        $user->referrer_settled = true;
        $user->save();
    }

    public  function makeWithdrawal(Request $request) {
        $amount = $request->amount;
        $currency = $request->currency;
        $user = Auth::user();

        $bank = Bank::find($request->bank);
        // Create Reference Code
        $reference = PaymentGateway::currency($currency)->generateReference();

        DB::beginTransaction();

        try {
            $user->debit($amount*100, $currency);
            $user->refresh();

            $amount = $amount * 100;
            $request->merge(['amount' => $amount, 'balance' => $user->balance(), 'reference' => $reference, 'bank_id' => $request->bank, 'status' => 'succeed']);
            $withdrawal = Withdrawal::create($request->all());

            $user->withdrawals()->save($withdrawal);

            // Send the money to the user
            $transfer = PaymentGateway::currency($currency)->withdraw($withdrawal);
            // $transfer = Flutterwave::transfers()->initiate($data);

            if ($transfer['status'] !== 'success') {
                $withdrawal->update(['status' => 'pending']);
            }

        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        return $withdrawal;
    }

    public function uploadImage(Request $request, string $uploadFolder = null) {
        $uploadFolder = is_null($uploadFolder) ? 'gift-cards' : $uploadFolder;
        // Save the image
        $file = $request->file('file');
        $filename = Str::slug(Str::random(2), '-') . time().'.'.$file->extension();

        $path = $file->storeAs(
            $uploadFolder , $filename
        );

        return $path;
    }

    public function sellCard(Request $request) {
        $user = auth()->user();
        $card = Card::find($request->card_id);
        $amount = $card->rate * $request->amount * 100;
        $unit = $request->amount;
        $bank = null;

        $data = array();

        $ref = $this->createReference('sell');

        DB::beginTransaction();

        try {

            if (isset($request->to_bank) && isset($request->bank)) {
                $bank = $request->bank;
            }

            // Save Images
            // if($request->hasfile('images')) {
            //     foreach($request->file('images') as $image)
            //     {
            //         $filename=$image->getClientOriginalName();
            //         // $image->move(public_path().'/images/', $name);
            //         $data[] = $filename;
            //         $path = $image->storeAs(
            //             'gift-cards', $filename
            //         );
            //     }

            // }

            $request->merge(['card_id' => $card->id, 'bank' => $bank, 'amount' => $amount, 'type'=> 'sell', 'balance' => $user->balance(), 'reference' => $ref, 'status' => 'pending', 'unit' => $unit]);
            // dd($request->all());

            $transaction = Transaction::create($request->all());
            $user->transactions()->save($transaction);

            if (is_null($request->images)) {
                $transaction->images = json_encode([]);
            }

            $transaction->save();

        } catch (Throwable $e) {
            DB::rollback();
            return back()->with('error', 'An error occured!');
        }

        DB::commit();

        // Send Notification to admin
        $this->newOrderNotifiction($transaction);

        // Return a response to the user
        return redirect()->route('transaction.index')->with('success', 'You request has been noted and will be attended to with 5-10 minutes.');
    }

    public function buyCard(Request $request) {
        $user = auth()->user();
        $card = Card::find($request->card_id);
        $amount = $card->rate * $request->amount * 100;
        $unit = $request->amount;

        // Debit the user
        //This generates a payment reference
        $reference = PaymentGateway::currency($request->currency)->generateReference();
        // $reference = Flutterwave::generateReference();

        DB::beginTransaction();

        try {
            $request->merge(['card_id' => $card->id, 'amount' => $amount, 'type'=> 'buy', 'balance' => $user->balance(), 'reference' => $reference, 'unit' => $unit]);
            // dd($request->all());

            $transaction = Transaction::create($request->all());
            $user->transactions()->save($transaction);
            $transaction->update([
                'admin_comment' => 'Payment pending!',
            ]);

            $payment = PaymentGateway::currency($request->currency)->makePayment($transaction, route('callback'));

            if ($payment['status'] !== 'success') {
                // notify something went wrong
                return back()->with('error', 'An error occured while processing payment!');
            }
            DB::commit();

            return redirect($payment['data']['link']);

        } catch (\Throwable $e) {
            DB::rollback();
            return back()->with('error', 'An error occured!');
        }


        // Return a response to the user
        return redirect()->route('transaction.index')->with('success', 'You request has been noted and will be attended to with 5-10 minutes.');
    }

    public function makeTransfer(Request $request, User $sender, User $recipient) {
        $amount = $request->amount * 100;

        if (isset($request->role)) {
            $role = $request->role;
        }

        DB::beginTransaction();

        try {

            if ($request->has('debit')) {
                // Credit users wallet with the amount (Only if the role is user or funder)
                if ($role == 'user') {
                    $sender->credit($amount);
                    $sender->refresh();
                }
                // Debit the recipients wallet with the amount
                $recipient->debit($amount);
                // Create Reference Code
                $ref = $this->createReference('reversal');
            } else {
                // Credit users wallet with the amount (Only if the role is user or funder)
                if ($role == 'user') {
                    $sender->debit($amount);
                    $sender->refresh();
                }
                // Debit the recipients wallet with the amount
                $recipient->credit($amount);
                // Create Reference Code
                $ref = $this->createReference('transfer');
            }

            // Create Transaction from request data
            $request->merge(['amount' => $amount, 'type'=> 'payout', 'role' => $role, 'balance' => $sender->balance(), 'reference' => $ref, 'recipient' => $recipient->id, 'status' => 'succeed', 'unit' => 0]);
            // dd($request);
            $transfer = Transaction::create($request->all());

            // Save users transaction
            $sender->transactions()->save($transfer);
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
            return back()->with('error', 'An error occured!');
        }

        DB::commit();

    }

    public function callback() {
        $reference = request()->has('tx_ref') ? request()->tx_ref : request()->transaction_id;
        // Get the transaction from your DB using the transaction reference
        $transaction = Transaction::where('reference', $reference)->first();
        if (is_null($transaction)) return redirect()->route('transaction.index')->with('error', 'Payment invalid and could not be processed!');

        if (request()->has('status') && request()->status == 'cancelled') {
            $transaction->delete();
            return redirect()->route('transaction.index')->with('error', 'Payment cancelled please try again.');
        }

        // $transactionID = Flutterwave::getTransactionIDFromCallback();

        $data = PayentGateway::currency($transaction->currency)->verifyTransaction();

        return $this->processCharge($data);
    }

    public function webhook(Request $request) {
        Log::info('Webhook received');
        //This verifies the webhook is sent from Flutterwave
        $verified = Flutterwave::verifyWebhook();

        // if it is a charge event, verify and confirm it is a successful transaction
        if ($verified && $request->event == 'charge.completed' && $request->data['status'] == 'successful') {
            Log::info('*** PAYMENT WEBHOOK ***');
            $verificationData = Flutterwave::verifyTransaction($request->data['id']);
            if ($verificationData['status'] === 'success') {
                // process for successful charge
                // value of true shows that its from a webhook
                $this->processCharge($verificationData, true);
            }

        }

        // if it is a transfer event, verify and confirm it is a successful transfer
        if ($verified && $request->event == 'transfer.completed') {
            Log::info('*** TRANSFER WEBHOOK ***');

            $transfer = Flutterwave::transfers()->fetch($request->data['id']);

            // Get the withdrawal from your DB using the withdrawal reference (reference)
            $withdrawal = Withdrawal::where('reference', $transfer['data']['reference'])->first();

            Log::info(json_encode($withdrawal));
            // exit();
            if($transfer['data']['status'] === 'SUCCESSFUL') {
                // update transfer status to successful in your db
                $withdrawal->status = 'succeed';
                $withdrawal->payment_status = 'succeed';
                $withdrawal->admin_comment = $transfer['data']['complete_message'];
            } else if ($transfer['data']['status'] === 'FAILED') {
                // update transfer status to failed in your db
                // revert customer balance back
                $withdrawal->payment_status = 'failed';
                $withdrawal->admin_comment = $transfer['data']['complete_message'];
            } else if ($transfer['data']['status'] === 'PENDING') {
                // update transfer status to pending in your db
                // initial state is pending
                $withdrawal->admin_comment = $transfer['data']['complete_message'];
            }

            $withdrawal->save();
        }
    }

    protected function createReference($type) {
        $reference = "CV" . Str::random(10);
        return strtoupper($reference);
    }

    protected function processCharge($data, $isWebhook = false) {
        Log::info(request()->all());


        if ($isWebhook) {
            // Get the transaction from your DB using the transaction reference (txref)
            $transaction = Transaction::where('reference', request()->data['tx_ref'])->first();
        } else {
            $reference = request()->has('tx_ref') ? request()->tx_ref : request()->transaction_id;
            // Get the transaction from your DB using the transaction reference
            $transaction = Transaction::where('reference', $reference)->first();

            // Get the transaction from your DB using the transaction reference (txref)
            // $transaction = Transaction::where('reference', request()->query('tx_ref'))->first();
        }

        // Handle when transaction is null. In case webhook is ahead of direct callback or vice-versa
        if (is_null($transaction)) {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('success', 'Invalid Transaction. This could mean the payment failed or was cancelled. Thank you!');
        }

        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        if ($transaction->payment_status === 'succeed' && $transaction->status === 'succeed') {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('success', 'Payment successful! Check the transaction tab for update. It should take 15-20 minutes!');
        }

        // ************ Old implementation ************** //
        // Confirm that the $data['data']['status'] is 'successful'
        // if ($data['data']['status']  !== 'successful') {
        //     $transaction->delete();
        //     return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed!');
        // }
        // // Confirm that the currency on your db transaction is equal to the returned currency
        // if ($data['data']['currency']  !== 'NGN') {
        //     $transaction->delete();
        //     return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed! [CE]');
        // }
        // // Confirm that the db transaction amount is equal to the returned amount
        // $amt = $transaction->amount/100;
        // if ($data['data']['amount']  !== $amt) {
        //     $transaction->delete();
        //     return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed! [AE]');
        // }
        // *************** Ends Here ***** //

        // ***** New Implementation ******** //
        if (!PaymentGateway::currency($transaction->currency)->isSuccessfulAndValid($data, $transaction)) {
            $transaction->delete();
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment not successful');
        }
        // ******** Ends Here ********* //

        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        $transaction->status = 'succeed';
        $transaction->payment_status = 'succeed';
        $transaction->save();
        // You can also redirect to your success page from here
        return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('success', 'Payment successful!  Check the transaction tab for update. It should take 15-20 minutes!');
    }

    public function addToAudienceList(User $user) {
        try {
            Newsletter::subscribeOrUpdate($user->email, ['FNAME'=> $user->username, 'PHONE' => $user->phonenumber], 'subscribers');
            Log::info('User added to email list successfully!');
        } catch (\Throwable $e) {
            Log::info(json_encode($e));
        }
    }

    public function newOrderNotifiction(Transaction $transaction) :void {
        // Fetch all admins
        $admins = User::role('admin')->get();
        Notification::send($admins, new Order($transaction));
    }

    public function notifyUser(Transaction $transaction) :void {
        // Get the user
        $user = $transaction->user;
        Notification::send($user, new OrderProcessed($transaction));
    }
}

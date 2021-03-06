<?php


namespace App\Services\API;


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

        // Credit the referrer
        $recipient->credit($bonus_amount);

        Transaction::create([
            'user_id'=> $recipient->id,
            'type'=>'payout',
            'balance' => $recipient->balance(),
            'reference' => $ref,
            'status' => 'succeed',
            'amount' => $bonus_amount,
            'unit' => 0
        ]);

        $user->referrer_settled = true;
        $user->save();
    }

    // Done ✔
    public  function makeWithdrawal(Request $request, $bank) {
        $amount = $request->amount;
        $user = Auth::user();

        // Create Reference Code
        $reference = Flutterwave::generateReference();

        DB::beginTransaction();

        try {
            // Make transfer here with flutterwave api
            $data = [
                "account_bank"=> $bank->code,
                "account_number"=> $bank->banknumber,
                "amount"=> $amount,
                "narration"=> "Cardvest - Funds withdrawal ".$reference,
                "currency"=>"NGN",
                "debit_currency"=>"NGN",
                'reference' => $reference
            ];

            $amount = $amount * 100;
            // If not successful, status of withdrawal remains pending
            $user->debit($amount);
            $user->refresh();

            $request->merge(['amount' => $amount, 'balance' => $user->balance(), 'reference' => $reference, 'bank_id' => $request->bank, 'status' => 'succeed']);
            $withdrawal = Withdrawal::create($request->all());

            $user->withdrawals()->save($withdrawal);

            // Send the money to the user
            $transfer = Flutterwave::transfers()->initiate($data);

            if ($transfer['status'] !== 'success') {
                $withdrawal->update(['status' => 'pending']);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        return $withdrawal;
    }

    // Pending
    public function uploadImage(Request $request) {
        // TODO:
        // Save the file in a temporary folder
        // Then move the file to the right folder when storing the sell transaction.
        // Save the image
        $file = $request->file('file');
        $filename = Str::slug(Str::random(2), '-') . time().'.'.$file->extension();

        $path = $file->storeAs(
            'gift-cards', $filename
        );

        return $filename;
    }

    // Done ✔
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
            if($request->hasfile('images')) {
                foreach($request->file('images') as $image)
                {
                    $filename = Str::slug(Str::random(2), '-') . time().'.'.$image->extension();
                    // $image->move(public_path().'/images/', $name);
                    $data[] = $filename;
                    $path = $image->storeAs(
                        'gift-cards', $filename
                    );
                }
            }

            $request->merge(['card_id' => $card->id, 'bank' => $bank, 'amount' => $amount, 'type'=> 'sell', 'balance' => $user->balance(), 'reference' => $ref, 'status' => 'pending', 'unit' => $unit, 'images' => 'data']);

            $transaction = Transaction::create($request->except(['images']));
            $user->transactions()->save($transaction);

            if (is_null($request->images)) {
                $transaction->images = json_encode([]);
            } else $transaction->images = json_encode($data);

            $transaction->save();

        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

        DB::commit();

        // Send Notification to admin
        $this->newOrderNotifiction($transaction);

        // Return a response to the user
        return [
            'message' => 'You request has been noted and will be attended to with 5-10 minutes.',
            'data' => $transaction,
        ];
    }

    // Done ✔
    public function buyCard(Request $request) {
        $user = auth()->user();
        $card = Card::find($request->card_id);
        $amount = $card->rate * $request->amount * 100;
        $unit = $request->amount;

        // Debit the user
        //This generates a payment reference
        $reference = Flutterwave::generateReference();

        DB::beginTransaction();

        try {
            $request->merge(['card_id' => $card->id, 'amount' => $amount, 'type'=> 'buy', 'balance' => $user->balance(), 'reference' => $reference, 'unit' => $unit]);

            $transaction = Transaction::create($request->all());
            $user->transactions()->save($transaction);
            $transaction->update([
                'admin_comment' => 'Payment pending!',
            ]);

            // Initialize Payment
            // Enter the details of the payment
            $data = [
                'amount' => $transaction->amount/100,
                'email' => $user->email,
                'tx_ref' => $reference,
                'currency' => "NGN",
                'redirect_url' => 'https://app.cardvest.ng/api/payment',
                'customer' => [
                    'email' => $user->email,
                    "phone_number" => $user->phonenumber,
                    "name" => $user->username
                ],

                "customizations" => [
                    "title" => "Cardvest",
                    "description" => "Buy $".$transaction->unit." ".$card->name." at ".$card->rate."/$",
                    "logo" => asset('images/logo-sm.png')
                ]
            ];

            $payment = Flutterwave::initializePayment($data);


            if ($payment['status'] !== 'success') {
                // notify something went wrong
                abort(400, 'An error occured while processing payment!');
            }
            DB::commit();

            return $payment['data']['link'];

        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }

    }

    // Remains thesame
    public function webhook(Request $request) {
        Log::info('Webhook received');
        //This verifies the webhook is sent from Flutterwave
        $verified = Flutterwave::verifyWebhook();
        Log::info($request['data']);
        // if it is a charge event, verify and confirm it is a successful transaction
        if ($verified && $request->event == 'charge.completed' && $request->data['status'] == 'successful') {
            Log::info('*** PAYMENT WEBHOOK ***');
            $verificationData = Flutterwave::verifyTransaction($request->data['id']);
            if ($verificationData['status'] === 'success') {
                // process for successful charge
                $this->processCharge($verificationData);
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

    // Remains thesame
    protected function createReference($type) {

        $random = Str::random(10);

        switch ($type) {
            case 'withdrawal':
                $reference = 'CVT'. $random .'WTH';
                break;
            case 'buy':
                $reference = 'CVT'. $random .'INV';
                break;
            case 'sell':
                $reference = 'CVT'. $random .'SEL';
                break;
            case 'deposit':
                $reference = 'CVT'. $random .'CDR';
                break;
            default:
                $reference = 'CVT'. $random .'-PAY';
                break;
        }
        return strtoupper($reference);
    }

    // Done ✔
    protected function processCharge($data) {
        Log::info(request()->data['tx_ref']);
        // Get the transaction from your DB using the transaction reference (txref)
        $transaction = Transaction::where('reference', request()->data['tx_ref'])->first();

        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        if ($transaction->payment_status === 'succeed' && $transaction->status === 'succeed') exit();

        // Confirm that the $data['data']['status'] is 'successful'
        if ($data['data']['status']  !== 'successful') exit();

        // Confirm that the currency on your db transaction is equal to the returned currency
        if ($data['data']['currency']  !== 'NGN') exit();

        // Confirm that the db transaction amount is equal to the returned amount
        $amt = $transaction->amount/100;
        if ($data['data']['amount']  !== $amt) exit();

        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        $transaction->status = 'succeed';
        $transaction->payment_status = 'succeed';
        $transaction->save();
        // You can also redirect to your success page from here
        exit();
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

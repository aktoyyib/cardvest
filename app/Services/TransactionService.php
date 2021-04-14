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

class TransactionService
{
    protected $role = 'user';
    const REFERRAL_BONUS = 50000; //kobo
    // const REGISTRATION_BONUS = 3000;

//    ALl amounts must be converted to kobo before processing

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

    public  function makeWithdrawal(Request $request) {
        $amount = $request->amount;
        $user = Auth::user();

        $bank = Bank::find($request->bank);
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
            
            // If not successful, status of withdrawal remains pending
            
            $user->debit($amount*100);
            $user->refresh();
            
            $amount = $amount * 100;
            $request->merge(['amount' => $amount, 'balance' => $user->balance(), 'reference' => $reference, 'bank_id' => $request->bank, 'status' => 'succeed']);
            $withdrawal = Withdrawal::create($request->all());
    
            $user->withdrawals()->save($withdrawal);

            // Send the money to the user
            $transfer = Flutterwave::transfers()->initiate($data);

            if ($transfer['status'] !== 'success') {
                $withdrawal->update(['status' => 'pending']);
            }
            
        } catch (\Throwable $e) {
            DB::rollback();
            return null;
        }
        
        DB::commit();

        return $withdrawal;
    }

    public function sellCard(Request $request) {
        $user = auth()->user();
        $card = Card::find($request->card_id);
        $amount = $card->rate * $request->amount * 100;
        $unit = $request->amount;
        $bank = null;
        
        $data = array();
        
        // return $amount;
        // Debit the user
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
                    $filename=$image->getClientOriginalName();
                    // $image->move(public_path().'/images/', $name);  
                    $data[] = $filename;
                    $path = $image->storeAs(
                        'gift-cards', $filename
                    );
                }
                
            }

            $request->merge(['card_id' => $card->id, 'bank' => $bank, 'amount' => $amount, 'type'=> 'sell', 'balance' => $user->balance(), 'reference' => $ref, 'status' => 'pending', 'unit' => $unit]);
            // dd($request->all());

            $transaction = Transaction::create($request->except('images'));
            $user->transactions()->save($transaction);

            $transaction->images = json_encode($data);
            $transaction->save();

        } catch (Throwable $e) {
            DB::rollback();
            return back()->with('error', 'An error occured!');
        }

        DB::commit();

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
        $reference = Flutterwave::generateReference();

        DB::beginTransaction();

        try {
            $request->merge(['card_id' => $card->id, 'amount' => $amount, 'type'=> 'buy', 'balance' => $user->balance(), 'reference' => $reference, 'unit' => $unit]);
            // dd($request->all());

            $transaction = Transaction::create($request->all());
            $user->transactions()->save($transaction);
            $transaction->update([
                'admin_comment' => 'Payment Received!',
            ]);
            
            // Initialize Payment
            // Enter the details of the payment
            $data = [
                'amount' => $transaction->amount/100,
                'email' => $user->email,
                'tx_ref' => $reference,
                'currency' => "NGN",
                'redirect_url' => route('callback'),
                'customer' => [
                    'email' => $user->email,
                    "phone_number" => $user->phonenumber,
                    "name" => $user->username
                ],

                "customizations" => [
                    "title" => "Buy $".$transaction->unit." ".$card->name." at ".$card->rate."/$",
                    "description" => $transaction->created_at
                ]
            ];

            $payment = Flutterwave::initializePayment($data);


            if ($payment['status'] !== 'success') {
                // notify something went wrong
                return back()->with('error', 'An error occured while processing payment!');
            }
            DB::commit();

            return redirect($payment['data']['link']);

        } catch (Throwable $e) {
            DB::rollback();
            return back()->with('error', 'An error occured!');
        }
        

        // Return a response to the user
        return redirect()->route('transaction.index')->with('success', 'You request has been noted and will be attended to with 5-10 minutes.');
    }

    public function makeTransfer(Request $request, User $sender, User $recipient) {
        $amount = $request->amount * 100;

        // if ($sender->id == $recipient->id) return;

        if (isset($request->role)) {
            $role = $request->role;
        }
        
        DB::beginTransaction();

        try {
            // Debit users wallet with the amount (Only if the role is user or funder)
            if ($role == 'user' || $role == 'funder') {
                $sender->debit($amount);
                $sender->refresh();
            }
            // Credit the recipients wallet with the amount
            $recipient->credit($amount);
            
            // Create Reference Code
            $ref = $this->createReference('transfer');

            // Create Transaction from request data
            $request->merge(['amount' => $amount, 'type'=> 'payout', 'role' => $role, 'balance' => $sender->balance(), 'reference' => $ref, 'recipient' => $recipient->id, 'status' => 'succeed', 'unit' => 0]);
            // dd($request);
            $transfer = Transaction::create($request->all());
            
            // Save users transaction
            $sender->transactions()->save($transfer);
        } catch (\Throwable $e) {
            dd($e);
            DB::rollback();
            return back()->with('error', 'An error occured!');
        }

        DB::commit();

    }

    public function callback() {
        $transactionID = Flutterwave::getTransactionIDFromCallback();
        $data = Flutterwave::verifyTransaction($transactionID);
        
        return $this->processCharge($data);
    }

    public function webhook(Request $request) {
        Log::info('Webhook received');
        //This verifies the webhook is sent from Flutterwave
        $verified = Flutterwave::verifyWebhook();

        // if it is a charge event, verify and confirm it is a successful transaction
        if ($verified && $request->event == 'charge.completed' && $request->data->status == 'successful') {
            Log::info('*** PAYMENT WEBHOOK ***');
            $verificationData = Flutterwave::verifyPayment($request->data['id']);
            if ($verificationData['status'] === 'success') {
                // process for successful charge
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

    protected function processCharge($data, $isWebhook = false) {
        // Get the transaction from your DB using the transaction reference (txref)
        $transaction = Transaction::where('reference', request()->query('tx_ref'))->first();
        
        // Check if you have previously given value for the transaction. If you have, redirect to your successpage else, continue
        if ($transaction->payment_status === 'succeed' && $transaction->status === 'succeed') {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('success', 'Payment successful!');
        }
        // Confirm that the $data['data']['status'] is 'successful'
        if ($data['data']['status']  !== 'successful') {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed!');
        }
        // Confirm that the currency on your db transaction is equal to the returned currency
        if ($data['data']['currency']  !== 'NGN') {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed! [CE]');
        }
        // Confirm that the db transaction amount is equal to the returned amount
        $amt = $transaction->amount/100;
        if ($data['data']['amount']  !== $amt) {
            return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('error', 'Payment failed! [AE]');
        }
        // Update the db transaction record (including parameters that didn't exist before the transaction is completed. for audit purpose)
        // Give value for the transaction
        // Update the transaction to note that you have given value for the transaction
        $transaction->status = 'succeed';
        $transaction->payment_status = 'succeed';
        $transaction->save();
        // You can also redirect to your success page from here
        return $isWebhook ===  true ? exit() : redirect()->route('transaction.index')->with('success', 'Payment successful!');
    }

    public function addToAudienceList(User $user) {
        $mailchimp = new \MailchimpMarketing\ApiClient();

        $mailchimp->setConfig([
        'apiKey' => env('MAILCHIMP_KEY'),
        'server' => env('MAILCHIMP_PREFIX')
        ]);

        // $response = $mailchimp->ping->get();
        // dd($response);

        $list_id = env('MAILCHIMP_LIST_ID');

        // $response = $mailchimp->lists->getListMembersInfo($list_id);
        // dd($response);

        try {
            $response = $mailchimp->lists->addListMember($list_id, [
                "email_address" => $user->email,
                "status" => "subscribed",
                "merge_fields" => [
                "FNAME" => $user->username,
                "PHONE" => $user->phonenumber
                ]
            ]);
            Log::info('User added to email list successfully!');
        } catch (\Throwable $e) {
            Log::info(json_encode($e));
        }
    }
}
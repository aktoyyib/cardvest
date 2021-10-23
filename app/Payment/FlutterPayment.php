<?php


namespace App\Payment;


use App\Models\Transaction;
use App\Models\Withdrawal;
use Illuminate\Support\Facades\Http;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterPayment implements Payment
{

    public function withdraw(Withdrawal $withdrawal) : array
    {
        $reference = $withdrawal->reference;
        $bank = $withdrawal->bank;
        $user = $withdrawal->user;

        try {
            // Make transfer here with flutterwave api
            $data = [
                "account_bank" => $bank->code,
                "account_number" => $bank->banknumber,
                "amount" => $withdrawal->amount,
                "narration" => "Cardvest - Funds withdrawal " . $reference,
                "currency" => $withdrawal->currency(),
                "debit_currency" => $withdrawal->currency(),
                'reference' => $reference
            ];

            // Send the money to the user
            $transfer = Flutterwave::transfers()->initiate($data);

            return $transfer;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function makePayment(Transaction $transaction, String $callback_url) : String
    {
        // Needed: transaction, card, amount, user, reference, callback_url
        $user = $transaction->user;
        $card = $transaction->card;

        try {
            // Initialize Payment
            // Enter the details of the payment
            $data = [
                'amount' => $transaction->amount/100,
                'email' => $user->email,
                'tx_ref' => $transaction->reference,
                'currency' => $user->currency(),
                'redirect_url' => $callback_url,
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
                abort(500, 'Payment could not be initialised');
            }

            return $payment['data']['link'];

        } catch (\Throwable $e) {
            throw $e;
        }

    }

    public function verifyTransaction(String $transactionID = null)
    {
        $transactionID = is_null($transactionID) ? Flutterwave::getTransactionIDFromCallback() : $transactionID;
        $data = Flutterwave::verifyTransaction($transactionID);
        return $data['data'];
    }

    public function verifyWebHook() : bool
    {
        return Flutterwave::verifyWebhook();
    }

    public function fetchBanks(): array
    {
        $banks = Flutterwave::banks()->nigeria();
        return $banks;
    }

    public function resolveAccount(String $accountnumber, String $bank)
    {
        $response = Http::withToken(config('flutterwave.secretKey'))
            ->post('https://api.flutterwave.com/v3/accounts/resolve', [
                'account_number' => $accountnumber,
                'account_bank' => $bank
            ]);

        return $response;
    }

    public function verifyTransfer(String $id): array
    {
        $transfer = Flutterwave::transfers()->fetch($id);
        return $transfer;
    }

    public function generateReference(): string
    {
        return Flutterwave::generateReference();
    }

    public function getReferenceFromCallback(): string
    {
        $reference = request()->tx_ref;
        return $reference;
    }

    public function isSuccessfulAndValid($data, Transaction $transaction): bool
    {
        if ($data['status']  !== 'successful') {
            return false;
        }

        // Confirm that the db transaction amount is equal to the returned amount
        $amt = $transaction->amount/100;
        if ($data['amount']  !== $amt) {
            return false;
        }

        return true;
    }
}
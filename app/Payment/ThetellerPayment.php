<?php


namespace App\Payment;


use App\Facades\Theteller;
use App\Models\Transaction;

class ThetellerPayment implements Payment
{

    public function withdraw(\App\Models\Withdrawal $withdrawal, string $currency): array
    {
        $reference = $withdrawal->reference;
        $bank = $withdrawal->bank;
        $user = $withdrawal->user;

        $description = "Cardvest - Funds withdrawal " . $reference;

        try {
            // Make transfer here with flutterwave api
            $data = [
                // "account_bank" => $bank->code,
                "account_issuer" => $bank->issuer,
                "account_number" => $bank->banknumber,
                "amount" => $withdrawal->amount,
                "processing_code"=>"404000",
                "r-switch"=> "FLT",
                'transaction_id' => $reference,
                'desc' => $description,
                'merchant_id' => config('theteller.merchantID'),
                "pass_code"=> config('theteller.passCode'),
            ];

            // Send the money to the user
            $transfer = Theteller::initiateTransfer($data);

            return $transfer;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function makePayment(Transaction $transaction, string $callback_url): string
    {
        // Needed: transaction, card, amount, user, reference, callback_url
        $user = $transaction->user;
        $card = $transaction->card;

        try {
            // Initialize Payment
            $data = [
                'amount' => Theteller::padAmount($transaction->amount), // In kobo
                'email' => $user->email,
                'redirect_url' => route('callback'),
                'transaction_id' => $transaction->reference,
                'desc' => "Buy $".$transaction->unit." ".$card->name." at ".$card->rate."/$",
                'merchant_id' => config('theteller.merchantID'),
            ];

            $payment = Theteller::initializePayment($data);


            if ($payment['status'] !== 'success') {
                // notify something went wrong
                dd($payment, $data);
                abort(500, 'Payment could not be initialised');
            }

            return $payment['checkout_url'];

        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function verifyTransaction(string $transactionID = null)
    {
        // Get the transaction id/reference for the payment
        $transactionID = is_null($transactionID) ? Theteller::getTransactionIDFromCallback() : $transactionID;
        // Then ping the server to verify the transaction
        $data = Theteller::verifyTransaction($transactionID);
        return $data;
    }

    public function verifyWebHook(): bool
    {
        // TODO: Implement verifyWebHook() method.
    }

    public function fetchBanks(): array
    {
        return [
            'status' => 'success',
            'data' => config('theteller.banks')
        ];
    }

    public function resolveAccount(string $accountnumber, string $bank)
    {
        // TODO: Implement resolveAccount() method.
    }

    public function verifyTransfer(string $id): array
    {
        return array();
    }

    public function generateReference(): string
    {
        return Theteller::generateReference();
    }

    public function getReferenceFromCallback(): string
    {
        $reference = request()->transaction_id;
        return $reference;
    }

    public function isSuccessfulAndValid($data, Transaction $transaction): bool
    {
        if ($data['status']  != 'approved' || $data['code'] != '000') {
            return false;
        }

        return true;
    }
}

<?php


namespace App\Payment;


use App\Facades\Theteller;
use App\Models\Withdrawal;
use App\Models\Transaction;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

class FlutterCedisPayment implements Payment
{

    public function withdraw(\App\Models\Withdrawal $withdrawal): array
    {
        $reference = $withdrawal->reference;
        $bank = $withdrawal->bank;
        $user = $withdrawal->user;

        try {
            // Make transfer here with flutterwave api
            $data = [
                "account_bank" => $bank->code, //MTN, TIGO, VODAFONE, AIRTEL | GH280100
                "account_number" => $bank->banknumber, //233xxxxxxxxxx | 0031625807099
                "amount" => $withdrawal->amount,
                "currency" => $withdrawal->currency,
                "beneficiary_name" => $bank->accountname,
                // "destination_branch_code" => "GH280103", // For Bank transfers
                "narration" => "Cardvest - Funds withdrawal " . $reference,
                "reference" => $reference,
            ];

            // Check if its mobile money or bank transfer
            if (!is_numeric($bank->code)) {
                $data['destination_branch_code'] = $bank->branch_code;
            }

            // Send the money to the user
            $transfer = Flutterwave::transfers()->initiate($data);
            return $transfer;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function makePayment(Transaction $transaction, string $callback_url): Array
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
                'currency' => $transaction->currency,
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
                ],
                // Please specify the following parameters in body: phone_number, network
            ];

            $payment = Flutterwave::initializePayment($data);

            return $payment;

        } catch (\Throwable $e) {
            throw $e;
        }

    }

    public function verifyTransaction(string $transactionID = null)
    {
        $transactionID = is_null($transactionID) ? Flutterwave::getTransactionIDFromCallback() : $transactionID;
        $data = Flutterwave::verifyTransaction($transactionID);
        return $data['data'];
    }

    public function verifyWebHook(): bool
    {
        return Flutterwave::verifyWebhook();
    }

    public function fetchBanks(): array
    {
        $banks = Flutterwave::banks()->ghana();
        // Filter out normal banks and leave mobile banks
        $mobileBanks = array_filter($banks['data'], function($bank) {
            return ctype_alpha($bank['code']);
        });
        $banks['data'] = $mobileBanks;
        return $banks;
    }

    public function resolveAccount(string $accountnumber, string $bank)
    {}

    public function verifyTransfer(string $id): array
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

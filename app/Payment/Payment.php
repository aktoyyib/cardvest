<?php

namespace App\Payment;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

interface Payment
{
    public function withdraw(\App\Models\Withdrawal $withdrawal, String $currency) : array;
    public function makePayment(Transaction $transaction, String $callback_url) : String;

    // Should return a 1-level array/object with status, currency and other relevant data
    public function verifyTransaction(String $transactionID = null);
    public function verifyWebHook() : bool;
    public function fetchBanks() : array;
    public function resolveAccount(String $accountnumber, String $bank);
    public function verifyTransfer(String $id) : array;
    public function generateReference() : String;
    public function getReferenceFromCallback() : String;
    public function isSuccessfulAndValid($data, Transaction $transaction) : bool;
}
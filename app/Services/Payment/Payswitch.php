<?php


namespace App\Services\Payment;

use Http;
use Str;

class Payswitch
{
    protected $userName;
    protected $apiKey;
    protected $baseUrl;
    protected $token;

    /**
     * Construct
     */
    function __construct()
    {

        $this->userName = config('theteller.userName');
        $this->apiKey = config('theteller.apiKey');
        $this->baseUrl = config('theteller.baseUrl');

        $this->token = base64_encode($this->userName . ":" . $this->apiKey);
    }

    public function healthCheck()
    {
        return $this->baseUrl;
    }

    /**
     * Generates a unique reference
     * @param $transactionPrefix
     * @return string
     */

    public function generateReference(String $transactionPrefix = NULL)
    {
        $str = substr(time(), -12);

        $len = strlen($str);
        if ($len < 12) {
            $str = '' . $str . substr(mt_rand(999, 999999), 0, (12 - $len)) . '';
        }

        return $str;
    }

    /**
     * Reaches out to Theteller to initialize a payment
     * @param $data
     * @return object
     */
    public function initializePayment(array $data)
    {

        $payment = Http::withHeaders([
                'Authorization' => 'Basic ' .$this->token
            ])->post(
                $this->baseUrl . '/checkout/initiate',
                $data
            )->json();

        return $payment;
    }


    /**
     * Gets a transaction ID depending on the redirect structure
     * @return string
     */
    public function getTransactionIDFromCallback()
    {
        $transactionID = request()->transaction_id;
        return $transactionID;
    }

    /**
     * Reaches out to Flutterwave to verify a transaction
     * @param $id
     * @return object
     */
    public function verifyTransaction($id)
    {
        // endpoint: /v1.1/users/transactions/Replace with transaction ID/status
        $data =  Http::withHeaders([
                'Authorization' => 'Basic ' .$this->token
            ])->get($this->baseUrl . "/v1.1/users/transactions/" . $id . '/status')->json();

        return $data;
    }

    /**
     * Banks
     * @return Banks
     */
    public function banks()
    {
        $banks = json_encode(config('theteller.banks'));
        return $banks;
    }

    /**
     * Initiate transfer to an account
     * @return Transfers
     */
    public function initiateTransfer($data)
    {

        $transfer = Http::withHeaders([
                'Authorization' => 'Basic ' .$this->token
            ])->post(
                $this->baseUrl . '/v1.1/transaction/process',
                $data
            )->json();

        return $transfer;
    }

    /**
     * Authorize the transfer after initiation
     * @return Transfers
     */
    public function authorizeTransfer($data)
    {

        $transfer = Http::withHeaders([
                'Authorization' => 'Basic ' .$this->token
            ])->post(
                $this->baseUrl . '/v1.1/transaction/bank/ftc/authorize',
                $data
            )->json();

        return $transfer;
    }

    public function padAmount(int $amt) : string
    {
        $l_a = 12; // Length of the amount string, padded by zeroes
        $left = $l_a - strlen($amt);

        $new_amt = '' . $amt;

        for ($i = 0; $i < $left; $i++) {
            $new_amt = '0' . $new_amt;
        }

        return $new_amt;
    }
}
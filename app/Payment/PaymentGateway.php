<?php


namespace App\Payment;

use App\Payment\FlutterPayment;
use App\Payment\ThetellerPayment;

class PaymentGateway
{
    public static function __callStatic($method, $arguments)
    {
        $currency = auth()->user()->currency();

        if ($currency == config('currency.list.nigeria')) {
            return (new FlutterPayment())->$method(...$arguments);
        }
        if ($currency == config('currency.list.ghana')) {
            return (new ThetellerPayment())->$method(...$arguments);
        }

        throw new \Exception('Invalid currency processing');
    }
}
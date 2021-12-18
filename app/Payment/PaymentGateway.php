<?php


namespace App\Payment;

use App\Payment\FlutterPayment;
use App\Payment\FlutterCedisPayment;

class PaymentGateway
{
    // Calls a specified function with arguement on the payment
    // class decided by the users default currency
    // This should be used sparingly
    public static function __callStatic($method, $arguments)
    {
        $currency = auth()->user()->currency();

        if ($currency == config('currency.list.nigeria')) {
            return (new FlutterPayment())->$method(...$arguments);
        }
        if ($currency == config('currency.list.ghana')) {
            return (new FlutterCedisPayment())->$method(...$arguments);
        }

        throw new \Exception('Invalid currency processing');
    }

    // Return the proper payment class based on a set currency
    // Recommended
    public static function currency(string $currency)
    {
        if ($currency == config('currency.list.nigeria')) {
            return (new FlutterPayment());
        }
        if ($currency == config('currency.list.ghana')) {
            return (new FlutterCedisPayment());
        }

        throw new \Exception('Invalid currency processing');
    }
}

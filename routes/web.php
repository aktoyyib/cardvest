<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('rates', [HomeController::class, 'rates'])->name('rates');

Route::get('fetch-banks', [WalletController::class, 'banks'])->name('banks');
Route::post('verify-bank', [WalletController::class, 'verify'])->name('verify');

Route::post('/webhook/flutterwave', [TransactionController::class, 'webhook'])->name('webhook');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('transactions/{transaction:reference}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::get('trade-card', [TransactionController::class, 'create'])->name('transaction.create');
    Route::post('trade-card/sell', [TransactionController::class, 'store'])->name('transaction.store');
    Route::post('trade-card/buy', [TransactionController::class, 'buy'])->name('transaction.buy');// The callback url after a payment
    Route::get('/rave/callback', [TransactionController::class, 'callback'])->name('callback');

    Route::post('withdraw-funds', [WithdrawalController::class, 'store'])->name('withdraw');

    Route::get('wallet', [WalletController::class, 'index'])->name('wallet.index');
    Route::post('wallet/bank/add', [WalletController::class, 'store'])->name('wallet.store');
    Route::delete('wallet/bank/remove/{bank}', [WalletController::class, 'destroy'])->name('wallet.removebank');
});
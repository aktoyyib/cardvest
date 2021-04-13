<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as Users;
use App\Http\Controllers\Admin\TransactionController as Transactions;
use App\Http\Controllers\Admin\WithdrawalController as Withdrawals;
use App\Http\Controllers\Admin\CategoryController as Categories;
use App\Http\Controllers\Admin\CardController as Cards;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Jobs\SendWelcomeMail;
use Illuminate\Support\Facades\Log;

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
Route::get('referrals', [HomeController::class, 'referral'])->name('referrals');

Route::get('email/220896/Sogo', function() {
    $user = App\Models\User::find(5);
    // SendWelcomeMail::dispatchAfterResponse($user);
    $mailchimp = new MailchimpMarketing\ApiClient();

    $mailchimp->setConfig([
    'apiKey' => env('MAILCHIMP_KEY'),
    'server' => env('MAILCHIMP_PREFIX')
    ]);

    // $response = $mailchimp->ping->get();
    // dd($response);

    $list_id = env('MAILCHIMP_LIST_ID');

    $response = $mailchimp->lists->getListMembersInfo($list_id);
    dd($response);

    return new App\Mail\WelcomeToCardvest($user);
});

Route::get('fetch-banks', [WalletController::class, 'banks'])->name('banks');
Route::post('verify-bank', [WalletController::class, 'verify'])->name('verify');

Route::post('/webhook/flutterwave', [TransactionController::class, 'webhook'])->name('webhook');

// Custom registration route
Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware(['guest', 'referral'])
                ->name('register');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index']);
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

    Route::group(['prefix' => 'admin', 'middleware' => ['role:admin']], function() {
        // Admin Dashboard
        Route::get('', AdminController::class)->name('admin.dashboard');
        Route::get('dashboard', AdminController::class)->name('admin.dashboard');
    
        Route::resource('users', Users::class)->only('index', 'show');
        
        Route::resource('transactions', Transactions::class);
    
        Route::resource('withdrawals', Withdrawals::class);
    
        Route::resource('categories', Categories::class);
    
        Route::resource('cards', Cards::class);
    });
});
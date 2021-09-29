<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TransactionController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\NotificationController;

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\UserController as Users;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\TransactionController as Transactions;
use App\Http\Controllers\Admin\WithdrawalController as Withdrawals;
use App\Http\Controllers\Admin\CategoryController as Categories;
use App\Http\Controllers\Admin\CardController as Cards;
use App\Http\Controllers\Admin\PushNotificationController;

use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use App\Jobs\SendWelcomeMail;
use Illuminate\Support\Facades\Log;
use App\Notifications\Order;
use App\Models\User;
use App\Models\Transaction;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Order as OrderNotification;
use App\Notifications\OrderProcessed;
use App\Services\TransactionService;

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

Route::get('chat', function() {
    return view('chat');
})->name('chat');

Route::get('/debug-sentry', function () {
    throw new Exception('My first Sentry error!');
});

Route::get('rates', [HomeController::class, 'rates'])->name('rates');

Route::get('/notification', function () {
    $transaction = Transaction::find(5);

    // return (new Order($transaction))
    //             ->toMail($transaction->user);
    // $admins = App\Models\User::role('admin')->get();
    $admins = $transaction->user;
    Notification::send($admins, new OrderProcessed($transaction));
    // Notification::route('mail', 'josephajibodu@gmail.com')->notify(new OrderProcessed($transaction));
    return (new OrderProcessed($transaction))
                ->toMail($transaction->user);
});

Route::get('email', function(TransactionService $transactionService) {
    $user = App\Models\User::find(5);
    // SendWelcomeMail::dispatchAfterResponse($user);
    dd($transactionService->addToAudienceList($user));
    // return new App\Mail\WelcomeToCardvest($user);
});

Route::view('/account/status', 'banned')->name('banned');

Route::get('fetch-banks', [WalletController::class, 'banks'])->name('banks');
Route::post('verify-bank', [WalletController::class, 'verify'])->name('verify');

Route::post('/webhook/flutterwave', [TransactionController::class, 'webhook'])->name('webhook');

// Custom registration route
Route::get('/register', [RegisteredUserController::class, 'create'])
                ->middleware(['guest', 'referral'])
                ->name('register');

Route::group(['middleware' => ['auth', 'ban']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/dashboard', [HomeController::class, 'index']);
    Route::get('profile', [HomeController::class, 'profile'])->name('profile');

    Route::get('notifications', [NotificationController::class, 'index'])->name('notifications');
    Route::get('referrals', [HomeController::class, 'referral'])->name('referrals');

    Route::get('transactions', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('transactions/{transaction:reference}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::post('trade-card/image', [TransactionController::class, 'upload'])->name('transaction.upload');
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

        Route::post('users/{user:username}/suspend', [Users::class, 'banUser'])->name('users.ban');
        Route::post('users/{user:username}/unsuspend', [Users::class, 'liftBan'])->name('users.liftban');
        Route::resource('users', Users::class)->only('index', 'show');

        Route::group(['middleware' => ['role:Super Admin']], function() {
            // Assign and Remove Role
            Route::get('roles/search', [RoleController::class, 'search'])->name('roles.search');
            Route::post('roles/store/{user}', [RoleController::class, 'store'])->name('roles.store');
            Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
            Route::delete('roles/remove/{user}', [RoleController::class, 'destroy'])->name('roles.destroy');

            Route::get('transactions/create', [Transactions::class, 'create'])->name('transactions.create');

            Route::get('activities', [\App\Http\Controllers\Admin\ActivityController::class, 'index'])->name('activities.index');
        });

        Route::get('transactions/search', [Transactions::class, 'search'])->name('transactions.search');
        Route::resource('transactions', Transactions::class)->only(['index', 'store', 'show', 'edit', 'update', 'destroy']);

        Route::resource('withdrawals', Withdrawals::class);

        Route::resource('categories', Categories::class);

        Route::delete('/cards/{card}/disable', [Cards::class, 'disable'])->name('cards.disable');
        Route::resource('cards', Cards::class);

        // Push Notification Campaign routes
        Route::get('/push-notifications', [PushNotificationController::class, 'index'])->name('push-notification-campaign');
        Route::post('/push-notifications/send', [PushNotificationController::class, 'pushNotification'])->name('push-notification-now');
    });
});

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\Category;
use App\Models\Card;

class AdminController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $users = User::count();
        $transactions = Transaction::desc()->count();
        $withdrawals = Withdrawal::count();
        $pending_withdrawals = Withdrawal::pending()->count();
        $categories = Category::count();
        $cards = Card::count();

        return view('admin.home', compact('users', 'transactions', 'withdrawals', 'pending_withdrawals', 'categories', 'cards'));
    }
}

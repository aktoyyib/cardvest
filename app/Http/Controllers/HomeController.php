<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use KingFlamez\Rave\Facades\Rave as Flutterwave;

use App\Models\Category;
use App\Models\Transaction;
use App\Models\Card;

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        $withdrawals = $user->withdrawals()->desc()->simplePaginate(3);
        $banks = $user->wallet->bank_accounts;
        $sold = $user->transactions()->completed()->cardSale()->sum('amount');
        $bought = $user->transactions()->completed()->cardPurchase()->sum('amount');
        $transactions = auth()->user()->transactions()->cardSaleOrPurchase()->desc()->take(3);

        $categories = Category::all();
        $cardsToBuy = Card::where('type', 'buy')->get();
        
        return view('home', compact('user', 'withdrawals', 'banks', 'sold', 'bought', 'transactions', 'categories', 'cardsToBuy'));
    }

    /**
     * Show the Profile page
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = auth()->user();
        $sold = $user->transactions()->completed()->cardSale()->sum('amount');
        $bought = $user->transactions()->completed()->cardPurchase()->sum('amount');
        $payouts = Transaction::completed()->desc()->payouts()->mine()->take(3)->get();
        // return $payouts;

        $details = array();

        $referral_bonus = $user->transactions()->type('referral')->get()->reduce(function($carry, $item){
            return $carry + $item["amount"];
        }, 0);

        $details['referral_bonus'] = $referral_bonus;
        $details['referrals'] = $user->referrals;

        $pending_referral = $user->referrals()->where('referrer_settled', false)->count();
        $details['pending_referrals'] = $pending_referral;

        // return $details;
        return view('profile', compact('user', 'sold', 'bought', 'payouts', 'details'));
    }

    public function rates()
    {
        $categories = Category::all();
        $cardsToBuy = Card::where('type', 'buy')->get();
        // return $categories;
        return view('rates', compact('categories', 'cardsToBuy'));
    }

    public function referral()
    {
        $user = auth()->user();
        
        $details = array();

        $referral_bonus = $user->transactions()->type('referral')->get()->reduce(function($carry, $item){
            return $carry + $item["amount"];
        }, 0);

        $details['referral_bonus'] = $referral_bonus;
        $details['referrals'] = $user->referrals;

        $pending_referral = $user->referrals()->where('referrer_settled', false)->count();
        $details['pending_referrals'] = $pending_referral;

        // return $details;
        return view('referrals', compact('user', 'details'));
    }
    
}
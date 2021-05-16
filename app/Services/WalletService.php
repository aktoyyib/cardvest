<?php


namespace App\Services;

use App\Models\Trader;
use App\Models\Transaction;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WalletService
{
  // To be used in separating the wallet feature from the website
  // $wallet->charge({user_id => 1, amount =>  20 }, wallet_id)
}
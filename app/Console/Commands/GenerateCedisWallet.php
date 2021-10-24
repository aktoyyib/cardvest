<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class GenerateCedisWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:wallet {currency}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Wallet for users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $currency = $this->argument('currency');
        // $currency = $this->argument('currency');
        $user = User::all();

        if (strtolower($currency) === 'count') {
            $this->comment('Currently we have '.$user->count(). ' users!');
            return 0;
        }

        if ($currency != 'GHS') return 0;

        $wallet = Wallet::create([
            'currency' => $currency
        ]);

        $wallet->user()->associate($user);
        $wallet->save();

        $this->comment('Currently we have '.$user->count(). ' users!');
        return 0;
    }
}

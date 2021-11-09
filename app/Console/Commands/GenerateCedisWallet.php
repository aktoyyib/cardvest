<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Wallet;

class GenerateCedisWallet extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'balance:wallet {currency} {name}';

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
        $this->warn('This action resets all users default wallet to Naira!');
        if (!$this->confirm('Do you wish to continue?')) {
            return 0;
        }

        $currency = $this->argument('currency');
        $name = $this->argument('name');

        $users = User::all();

        if (strtolower($currency) === 'count') {
            $this->comment('Currently we have '.$user->count(). ' users!');
            return 0;
        }

        if ($currency != 'GHS') return 0;

        // Progress Bar should be implemented

        // Make all users Naira wallet default
        $wallets = Wallet::where('currency', 'NGN')->get();
        foreach ($wallets as $wallet) {
            $wallet->isDefault = true;
            $wallet->name = "Naira";
            $wallet->save();
        }

        foreach ($users as $user) {
            if (is_null($user->fiat_wallets()->currency($currency)->first())) {
                $wallet = Wallet::create([
                    'currency' => $currency,
                    'name' => $name
                ]);

                $wallet->user()->associate($user);
                $wallet->save();
            }
        }

        $this->comment(ucfirst($name) . ' wallet created for all users successfully!');
        return 0;
    }
}

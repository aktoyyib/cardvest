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
    protected $signature = 'balance:wallet -c {currency} -n {name}';

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
        $name = $this->argument('name');

        $users = User::all();

        if (strtolower($currency) === 'count') {
            $this->comment('Currently we have '.$user->count(). ' users!');
            return 0;
        }

        if ($currency != 'GHS') return 0;

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

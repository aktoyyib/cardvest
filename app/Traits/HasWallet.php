<?php


namespace App\Traits;

use App\Models\Wallet;


trait HasWallet
{
    // Returns the default wallet
    public function wallet() {
        return $this->hasOne('App\Models\Wallet')->where('isDefault', true);
    }

    // Returns all wallets
    public function fiat_wallets() {
        return $this->hasMany('App\Models\Wallet')->where('type', 'fiat');
    }

    /**
     * Determine if the user can withdraw the given amount
     * @param  integer $amount (in kobo)
     * @return boolean
     */
    public function isSufficient($amount, string $currency = null)
    {
        $wallet = $this->getWallet($currency);

        return $wallet->balance >= $amount;
    }

    /**
     * Determine if users balance + bonus is sufficient
     * @param  integer $amount
     * @return boolean
     */
    public function isAbsolutelySufficient($amount, string $currency = null)
    {
        $wallet = $this->getWallet($currency);

        // Get the total balance (Balance + Bonus)
        $balance = $wallet->balance;
        $bonus = $wallet->bonus;
        return ($balance + $bonus) >= $amount;
    }

    /**
     * Crediting the users wallet
     * @param integer $amount (in kobo)
     * @param bool $bonus
     */
    public function credit($amount, string $currency = null, $bonus = false)
    {
        $wallet = $this->getWallet($currency);

        if ($bonus) {
            $balance = $wallet->bonus + $amount;
            $wallet->update(['bonus' => $balance]);
        } else {
            $balance = $wallet->balance + $amount;
            $wallet->update(['balance' => $balance]);
        }
    }

    /**
     * Move credits to this account
     * @param integer $amount (in kobo)
     * @param bool $bonus
     */
    public function debit($amount, string $currency = null, $bonus = false)
    {
        $wallet = $this->getWallet($currency);

        $balance = $wallet->balance;
        $bonusBal = $wallet->bonus;

        // To know if the bonus will be spent
        // Negative value indicates that Main Balance can bear the charges
        // Positive value indicates that Main Balance can't bear all the charges, hence, Bonus will also be used.
        $deficit = $amount - $balance;

        // Check if normal balance can cover the amount
        // If not debit the bonus wallet too.
        if ($bonus && ($deficit > 0)) {
            // For Bonus to be considered, then balance is not enough and should now be 0
            $wallet->update(['balance' => 0]);

            // Deduct the deficit from the bonus
            $bonusBalance = $bonusBal - $deficit;

            $wallet->update(['bonus' => $bonusBalance]);
        } else {
            $balance = $wallet->balance - $amount;

            $wallet->update(['balance' => $balance]);
        }


    }

    public function balance(string $currency = null) {
        $wallet = $this->getWallet($currency);

        return $wallet->balance;
    }

    public function getTotalBalance(string $currency = null) {
        $wallet = $this->getWallet($currency);

        $balance = $wallet->balance;
        $bonus = $wallet->bonus;
        return ($balance + $bonus);
    }

    public function curency() {
        return $this->wallet->currency;
    }

    protected function getWallet(string $currency = null) : Wallet
    {
        if (is_null($currency)) return $this->wallet;
        else return $this->fiat_wallets()->currency($currency)->first();
    }
}

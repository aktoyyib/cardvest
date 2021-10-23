<?php


namespace App\Traits;


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
        if (is_null($currency))
            $wallet = $this->wallet;
        else
            $wallet = $this->fiat_wallets()->currency($currency);

        return $wallet->balance >= $amount;
    }

    /**
     * Determine if users balance + bonus is sufficient
     * @param  integer $amount
     * @return boolean
     */
    public function isAbsolutelySufficient($amount, string $currency = null)
    {
        if (is_null($currency)) $wallet = $this->wallet;
        else $wallet = $this->fiat_wallets()->currency($currency);

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
    public function credit($amount, $bonus = false, string $currency = null)
    {
        if (is_null($currency)) $wallet = $this->wallet;
        else $wallet = $this->fiat_wallets()->currency($currency);

        if ($bonus) {
            $balance = $wallet->bonus + $amount;
            $wallet()->update(['bonus' => $balance]);
        } else {
            $balance = $wallet->balance + $amount;
            $wallet()->update(['balance' => $balance]);
        }
    }

    /**
     * Move credits to this account
     * @param integer $amount (in kobo)
     * @param bool $bonus
     */
    public function debit($amount, $bonus = false, string $currency = null)
    {
        if (is_null($currency)) $wallet = $this->wallet;
        else $wallet = $this->fiat_wallets()->currency($currency);

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
            $wallet()->update(['balance' => 0]);

            // Deduct the deficit from the bonus
            $bonusBalance = $bonusBal - $deficit;

            $wallet()->update(['bonus' => $bonusBalance]);
        } else {
            $balance = $wallet->balance - $amount;

            $wallet()->update(['balance' => $balance]);
        }


    }

    public function balance() {
        return $this->wallet->balance;
    }

    public function getTotalBalance() {
        $balance = $this->wallet->balance;
        $bonus = $this->wallet->bonus;
        return ($balance + $bonus);
    }

    public function curency() {
        return $this->wallet->currency;
    }

}

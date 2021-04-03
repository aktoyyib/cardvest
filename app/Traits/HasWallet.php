<?php


namespace App\Traits;


trait HasWallet
{
    public function wallet() {
        return $this->hasOne('App\Models\Wallet');
    }

    /**
     * Determine if the user can withdraw the given amount
     * @param  integer $amount (in kobo)
     * @return boolean
     */
    public function isSufficient($amount)
    {
        return $this->wallet->balance >= $amount;
    }

    /**
     * Determine if users balance + bonus is sufficient
     * @param  integer $amount
     * @return boolean
     */
    public function isAbsolutelySufficient($amount)
    {
        // Get the total balance (Balance + Bonus)
        $balance = $this->wallet->balance;
        $bonus = $this->wallet->bonus;
        return ($balance + $bonus) >= $amount;
    }

    /**
     * Crediting the users wallet
     * @param integer $amount (in kobo)
     * @param bool $bonus
     */
    public function credit($amount, $bonus = false)
    {
        if ($bonus) {
            $balance = $this->wallet->bonus + $amount;
            $this->wallet()->update(['bonus' => $balance]);
        } else {
            $balance = $this->wallet->balance + $amount;
            $this->wallet()->update(['balance' => $balance]);
        }
    }

    /**
     * Move credits to this account
     * @param integer $amount (in kobo)
     * @param bool $bonus
     */
    public function debit($amount, $bonus = false)
    {
        $balance = $this->wallet->balance;
        $bonusBal = $this->wallet->bonus;

        // To know if the bonus will be spent
        // Negative value indicates that Main Balance can bear the charges
        // Positive value indicates that Main Balance can't bear all the charges, hence, Bonus will also be used.
        $deficit = $amount - $balance;

        // Check if normal balance can cover the amount
        // If not debit the bonus wallet too.
        if ($bonus && ($deficit > 0)) {
            // For Bonus to be considered, then balance is not enough and should now be 0
            $this->wallet()->update(['balance' => 0]);

            // Deduct the deficit from the bonus
            $bonusBalance = $bonusBal - $deficit;

            $this->wallet()->update(['bonus' => $bonusBalance]);
        } else {
            $balance = $this->wallet->balance - $amount;

            $this->wallet()->update(['balance' => $balance]);
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

}

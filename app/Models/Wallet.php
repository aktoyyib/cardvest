<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wallet extends Model
{
    use HasFactory;

    /**
     * The relations loaded with the class.
     *
     * @var array
     */
    protected $with = ['bank_accounts'];
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank_accounts()
    {
        return $this->hasMany(Bank::class);
    }

    public function getBankName()
    {
        return config('banks')[$this->bankname];
    }

    public function getRawBankName()
    {
        return $this->banks[$this->bankname];
    }

    public function makeDefault()
    {
        $this->isDefault = true;
        $this->save();
    }

    public function removeDefault()
    {
        $this->isDefault = false;
        $this->save();
    }

    public function scopeCurrency($query, $currency)
    {
        $query->where('currency', $currency)->first();
    }
}

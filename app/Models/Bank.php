<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }

    public function withdrawals() {
        return $this->hasMany(Withdrawal::class);
    }
}
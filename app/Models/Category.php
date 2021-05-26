<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $with = ['cards_you_sell', 'cards_you_buy'];

    protected $guarded = ['id'];

    public function cards_you_sell()
    {
        return $this->hasMany(Card::class)->where('type', 'sell');
    }

    public function cards_you_buy()
    {
        return $this->hasMany(Card::class)->where('type', 'buy');
    }

    public function all_cards()
    {
        return $this->hasMany(Card::class);
    }
}
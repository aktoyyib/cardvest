<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $with = ['cards_you_sell', 'cards_you_buy', 'cards'];

    protected $guarded = ['id'];

    public function cards_you_sell()
    {
        return $this->hasMany(Card::class)->where('type', 'sell')->active()->live();
    }

    // To support migration cards should be changed to cards_you_sell
    public function cards()
    {
        return $this->hasMany(Card::class)->where('type', 'sell')->active()->live();
    }

    public function cards_you_buy()
    {
        return $this->hasMany(Card::class)->where('type', 'buy')->active()->live();
    }

    public function all_cards()
    {
        return $this->hasMany(Card::class)->live();
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $with = ['cards', 'all_cards'];

    protected $guarded = ['id'];

    public function cards()
    {
        return $this->hasMany(Card::class)->where('type', 'sell');
    }

    public function all_cards()
    {
        return $this->hasMany(Card::class);
    }
}
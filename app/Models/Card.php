<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Card extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function scopeSale($query)
    {
        return $query->where('type', 'sell');
    }

    public function scopeBuy($query)
    {
        return $query->where('type', 'buy');
    }

    public function scopeAlpha($query)
    {
        return $query->orderBy('name', 'asc');
    }

    public function scopeType($query, $value) {
        return $query->where('type', $value);
    }

    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    public function scopeLive($query)
    {
        return $query->where('deleted', false);
    }

    public function lightDelete() {
        $this->update(['deleted' => true]);
    }
}

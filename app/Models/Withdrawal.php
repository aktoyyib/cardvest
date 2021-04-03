<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Withdrawal extends Model
{
    use HasFactory;

    protected $guarded = [
        'id',
    ];

    public function getDate()
    {
        return date('m-d-Y', strtotime($this->created_at));
    }

    public function getTime()
    {
        return date('H:i A', strtotime($this->created_at));
    }

    public function getStatus() {
        $label = 'pending';
        switch ($this->status) {
            case 'pending':
                $label = 'warning';
                break;
            case 'closed':
                $label = 'danger';
                break;
            case 'succeed':
                $label = 'success';
                break;
            default:
                # code...
                break;
        }

        return $label;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function scopeDesc($query)
    {
        $query->orderBy('created_at', 'desc');
    }

    public function scopePending($query)
    {
        $query->where('status', 'pending');
    }
}
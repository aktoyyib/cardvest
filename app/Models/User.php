<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasWallet;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasWallet;//, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username',
        'phonenumber',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The relations loaded with the class.
     *
     * @var array
     */
    protected $with = ['wallet'];

    // Recepients of transfer from admin
    public function myPayouts()
    {
        return $this->hasMany(Transaction::class, 'recipient');
    }

    // Transfers made by Admin
    public function payouts()
    {
        return $this->hasMany(Transaction::class)->where('role', 'admin');
    }

    // Withdrawal Requests
    public function withdrawals()
    {
        return $this->hasMany(Withdrawal::class);
    }

    // Transactions  of cards
    public function transactions()
    {
        return $this->hasMany(Transaction::class)->where('role', 'user');
    }
    
    public function scopeSearch($query, $username)
    {
        $query->where('email', $username)->orWhere('username', $username);
    }
}
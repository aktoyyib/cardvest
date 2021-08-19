<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Laravel\Sanctum\HasApiTokens;
use Spatie\Activitylog\Traits\CausesActivity;
use Spatie\Permission\Traits\HasRoles;
use App\Traits\HasWallet;
use App\Traits\Referral;
use App\Traits\Banning;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasWallet, HasRoles, Referral, CausesActivity, Banning;

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
        'referrer_id',
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

    public function getId() {
        return "CV".str_pad($this->id,  6, "0", STR_PAD_LEFT);
    }

    public function getInitials() {
        return strtoupper(substr($this->username, 0, 1).substr($this->email, 0, 1));
    }

    public function getTotalSold() {
        return $this->transactions()->cardSale()->sum('amount');
    }

    public function getTotalBought() {
        return $this->transactions()->cardPurchase()->sum('amount');
    }

    /**
     * Route notifications for the Slack channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForSlack($notification)
    {
        return 'https://hooks.slack.com/services/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';
    }
}

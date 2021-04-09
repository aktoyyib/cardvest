<?php


namespace App\Traits;


use App\Models\User;

trait Referral
{
    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
//    protected $appends = ['referral_link'];

    /**
     * Get the user's referral link.
     *
     * @return string
     */
    public function getReferralLinkAttribute() {
        return $this->referral_link = route('register', ['ref' => $this->username]);
    }

    public function referrer() {
        return $this->belongsTo(User::class, 'referrer_id', 'id');
    }

    public function referrals() {
        return $this->hasMany(User::class, 'referrer_id', 'id');
    }
}
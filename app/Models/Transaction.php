<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class Transaction extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $guarded = [
        'id',
    ];

    protected $with = ['card', 'bank', 'recipient'];

    protected static $logAttributes = ['type', 'reference', 'images', 'amount', 'status', 'payment_status', 'admin_comment'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'admin.transactions';

    public function getDate()
    {
        return date('m-d-Y', strtotime($this->created_at));
    }

    public function getTime()
    {
        return date('h:i A', strtotime($this->created_at));
    }

    public function getDescription($key = null) {
        $label = 'success';

        if (!is_null($key)) {
            switch ($key) {
                case 'succeed':
                    $label = 'success';
                    break;
                case 'rejected':
                    $label = 'danger';
                    break;
                case 'pending':
                    $label = 'warning';
                    break;
                default:
                    # code...
                    break;
            }
        } else {

            switch ($this->type) {
                case 'sell':
                    $label = 'success';
                    break;
                case 'buy':
                    $label = 'danger';
                    break;
                case 'payout':
                    $label = 'warning';
                    break;
                default:
                    # code...
                    break;
            }
        }

        return $label;
    }

    public function getStatus() {
        $label = 'pending';
        switch ($this->status) {
            case 'pending':
                $label = 'progress';
                break;
            case 'rejected':
                $label = 'canceled';
                break;
            case 'succeed':
                $label = 'approved';
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

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient', 'id');
    }

    public function payout_to() {
        return $this->belongsTo(User::class, 'recipient');
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function scopeDesc($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeReferralEarnings($query, $user)
    {
        return $query->where('type', 'referral');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeHasRecipient($query)
    {
        return $query->where('recipient', '!=', null);
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin')->where('user_id', auth()->id());
    }

    public function scopeNotAdmin($query)
    {
        return $query->where('role', 'user');
    }

    public function scopeFunder($query)
    {
        return $query->where('role', 'funder')->where('user_id', auth()->id());
    }

    public function scopeMine($query) {
        return $query->where('recipient', auth()->id());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'succeed');
    }

    public function scopeCardSale($query)
    {
        return $query->where('type', 'sell');
    }

    public function scopeCardPurchase($query)
    {
        return $query->where('type', 'buy');
    }

    public function scopeCardSaleOrPurchase($query)
    {
        return $query->where('type', 'buy')->orWhere('type', 'sell');
    }

    public function scopePayouts($query)
    {
        return $query->where('type', 'payout');
    }

    public function scopeType($query, $value)
    {
        if ($value == 'referral')
            return $query->where('type', 'payout')->where('reference', 'REFERRAL_BONUS');
        return $query->where('type', $value);
    }
}

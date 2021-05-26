<?php

namespace App\Http\Resources\Withdrawal;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Bank\BankResource;
use App\Http\Resources\Bank\BankCollection;

class WithdrawalResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'reference' => $this->reference,
            'bank' => new BankResource($this->bank),
            'amount' => $this->amount,
            'balance' => $this->balance,
            'status' => $this->status,
            'payment_status' => $this->payment_status,
            'admin_comment' => $this->admin_comment,
            'created_at' => $this->created_at
        ];
    }
}

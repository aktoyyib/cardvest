<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;

class TransactionResource extends JsonResource
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
            "id" => $this->id,
            "user_id" => $this->user_id,
            "type" => $this->type,
            "card_id" => $this->card_id,
            "reference" => $this->reference,
            "bank_id" => $this->bank_id,
            "images" => $this->images,
            "amount" => $this->amount,
            "unit" => $this->unit,
            "balance" => $this->balance,
            "status" => $this->status,
            "payment_status" => $this->payment_status,
            "role" => $this->role,
            "recipient" => $this->recipient,
            "comment" => $this->comment,
            "admin_comment" => $this->admin_comment,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            "bank" => $this->bank
        ];
    }
}

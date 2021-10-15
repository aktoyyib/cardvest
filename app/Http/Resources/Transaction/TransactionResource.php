<?php

namespace App\Http\Resources\Transaction;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Card\CardResource;

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
            "admin_images" => $this->admin_images,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "deleted_at" => $this->deleted_at,
            'card' => new CardResource($this->card),
            "bank" => $this->bank
        ];
    }
}

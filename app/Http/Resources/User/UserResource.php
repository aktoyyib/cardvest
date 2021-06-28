<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Resources\Json\JsonResource;

use App\Http\Resources\Wallet\WalletResource;

class UserResource extends JsonResource
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
            "username" => $this->username,
            "phonenumber" => $this->phonenumber,
            "email" => $this->email,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "referrer_id" => $this->referrer_id,
            "referrer_settled" => $this->referrer_settled,
            "referral_code" => $this->referral_code,
            'wallets' => new WalletResource($this->wallet),
        ];
    }
}

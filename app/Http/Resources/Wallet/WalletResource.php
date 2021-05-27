<?php

namespace App\Http\Resources\Wallet;

use App\Http\Resources\Bank\BankResource;
use Illuminate\Http\Resources\Json\JsonResource;

class WalletResource extends JsonResource
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
            "bonus" => $this->bonus,
            "balance" => $this->balance,
            "currency" => $this->currency,
            "bank_accounts" => new BankResource($this->bank_accounts)
        ];
    }
}

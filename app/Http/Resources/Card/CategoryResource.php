<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Card\CardCollection;

class CategoryResource extends JsonResource
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
            "name" => $this->name,
            "description" => $this->description,
        ];
    }
}

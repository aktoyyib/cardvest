<?php

namespace App\Http\Resources\Card;

use Illuminate\Http\Resources\Json\JsonResource;

class CardResource extends JsonResource
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
            "rate" => $this->rate,
            "min" => $this->min,
            "max" => $this->max,
            "type" => $this->type,
            "category_id" => $this->category_id,
            "created_at" => $this->created_at,
            "updated_at" => $this->updated_at,
            "terms" => $this->terms,
            "denomination" => $this->denomination
        ];
    }
}

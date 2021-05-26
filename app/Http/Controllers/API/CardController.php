<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Card;
use App\Http\Resources\Card\CardResource;

class CardController extends Controller
{
    public function index() {
        return CardResource::collection(Card::all());
    }

    public function show(Card $card) {
        return new CardResource($card);
    }
}

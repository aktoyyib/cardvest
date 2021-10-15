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

    public function cardsUsersCanSell()
    {
        return CardResource::collection(Card::sale()->get());
    }

    public function cardsUsersCanBuy()
    {
        // return CardResource::collection(Card::buy()->get());
        return CardResource::collection(Card::active()->live()->buy()->get());
    }
}
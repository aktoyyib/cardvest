<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Category;
use App\Models\Card;
use App\Http\Resources\Card\CardResource;
use App\Http\Resources\Card\CardCollection;
use App\Http\Resources\Card\CategoryResource;
use App\Http\Resources\Card\CategoryCollection;

class CategoryController extends Controller
{
    public function index()
    {
        return new CategoryCollection(Category::all());
    }

    public function cardsUsersCanSell(Category $category)
    {
        return new CardCollection($category->cards_you_sell);
    }

    public function cardsUsersCanBuy(Category $category)
    {
        return new CardCollection($category->cards_you_buy);
    }

}

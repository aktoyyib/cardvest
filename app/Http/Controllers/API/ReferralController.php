<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ReferralResource;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    public function index()
    {
        return ReferralResource::collection(auth()->user()->referrals);
    }
}

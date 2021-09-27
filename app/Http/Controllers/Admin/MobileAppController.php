<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MobileApp;
use Illuminate\Http\Request;

class MobileAppController extends Controller
{
    public function index()
    {
        // Show list of mobile phones with installed apps
        
    }

    public function storeToken(Request $request)
    {
        // Expected parameters
        $param = 
        [
            'token' => 'ExponentToken[******************]',
            'description' => '- Android X',
            'user_id' => 1,
            'type' => 'beat'
        ];
    }

    public function destroy(MobileApp $mobileApp)
    {
        // Delete a mobile apps token
    }
}

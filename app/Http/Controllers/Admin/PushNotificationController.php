<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MobileApp;
use Carbon\Carbon;

class PushNotificationController extends Controller
{
    public function index()
    {
        return view('admin.push-notifications.index');
    }

    public function pushNotification(Request $request)
    {
        //
    }

    public function storeToken(Request $request)
    {
        // Expected parameters
        // $param = 
        // [
        //     'token' => 'ExponentToken[******************]',
        //     'description' => '- Android X',
        //     'user_id' => 1,
        //     'type' => 'beat' or 'registers'
        // ];
        $request->validate([
            'token' => ['required', 'string',  'max:255'],
            'description' => ['required', 'string',  'max:255'],
            'user_id' => ['required', 'numeric',  'min:1'],
            'type' => ['required', 'string',  Rule::in(['beat', 'register'])]
        ]);

        // Check if its a beat or register
        if ($request->type != 'beat') {
            MobileApp::create($request->except(['type']));
        } else {
            $mobile = MobileApp::where('token', $request->token)->first();
            
            if (is_null($mobile)) {
                return;
            }
            
            $mobile->update([
                'user_id' => $request->user_id,
                'description' => $request->description,
                'last_seen' => Carbon::now()->toDateTimeString()
            ]);
        }
    }

    public function viewNotifications($id)
    {
        //
    }

}

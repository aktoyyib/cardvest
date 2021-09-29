<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\MobileApp;
use Carbon\Carbon;

use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;

class PushNotificationController extends Controller
{
    protected $channels = [
        'default' => 'general',
        'notifications' => 'campaign'
    ];

    public function index()
    {
        // return view('admin.push-notifications.index');$channelName = 'news';
    }

    public function pushNotification(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string',  'max:255'],
            'body' => ['required', 'string'],
            'channel' => ['required', 'string',  Rule::in(['default', 'notification'])]
        ]);

        $channelName = $channels[$request->channel];
        
        // You can quickly bootup an expo instance
        $expo = \ExponentPhpSDK\Expo::normalSetup();
        
        // Build the notification data
        $notification = [
            'title'  => $request->title,
            'body' => $request->body,
            'data' => json_encode(array(
                'type' => 'modal',//transactional
                // 'page' => 'transaction'
            ))
        ];
        
        // Notify an interest with a notification
        $expo->notify([$channelName], $notification);
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
            $channelName = $channels['default'];
            $recipient= $request->token;
            
            // You can quickly bootup an expo instance
            $expo = \ExponentPhpSDK\Expo::normalSetup();
            
            // Subscribe the recipient to the server
            $expo->subscribe($channelName, $recipient);
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

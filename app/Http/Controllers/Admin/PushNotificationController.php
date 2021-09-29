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
    public function index()
    {
        // return view('admin.push-notifications.index');$channelName = 'news';
        $channelName = 'news';
        $recipient= 'ExponentPushToken[0yOCmBN4Aj8WRMqNUsHm-O]';
        
        // You can quickly bootup an expo instance
        $expo = \ExponentPhpSDK\Expo::normalSetup();
        
        // Subscribe the recipient to the server
        $expo->subscribe($channelName, $recipient);
        
        // Build the notification data
        $notification = ['body' => 'Hello World!'];
        
        // Notify an interest with a notification
        $expo->notify([$channelName], $notification);
    }

    public function pushNotification(Request $request)
    {
        // Construct the push notification object
        // Send it with expo-sdk-package
        $channelName = 'news';
        $recipient= 'ExponentPushToken[0yOCmBN4Aj8WRMqNUsHm-O]';
        
        // You can quickly bootup an expo instance
        $expo = \ExponentPhpSDK\Expo::normalSetup();
        
        // Subscribe the recipient to the server
        $expo->subscribe($channelName, $recipient);
        
        // Build the notification data
        $notification = ['body' => 'Hello World!'];
        
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

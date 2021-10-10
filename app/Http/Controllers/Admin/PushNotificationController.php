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
        return view('admin.push-notifications.index');
    }

    public function health()
    {
        $expo = \ExponentPhpSDK\Expo::normalSetup();

        return response('Push Notification channel is healthy');
    }

    public function pushNotification(Request $request)
    {
        $request->validate([
            'title' => ['required', 'string',  'max:255'],
            'body' => ['required', 'string'],
            'channel' => ['required', 'string',  Rule::in(['default', 'notifications'])]
        ]);

        $channelName = $this->channels[$request->channel];
        
        // You can quickly bootup an expo instance
        $expo = \ExponentPhpSDK\Expo::normalSetup();
        
        // Build the notification data
        $notification = [
            'title'  => $request->title,
            'body' => $request->body,
            'data' => json_encode(array(
                'type' => 'news',
                'url' => 'cardvest://news'
            ))
        ];
        
        try {// Notify an interest with a notification
            $expo->notify([$channelName], $notification);
        } catch (\ExponentPhpSDK\Exceptions\UnexpectedResponseException $e) {
            return back()->with('error', 'Unable to send push notification campaign now. Try again later.');
        }

        return back()->with('success', 'Push Notification Campaign sent successfully!');
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
            $channelName = $this->channels['notifications'];
            $recipient= $request->token;
            
            // You can quickly bootup an expo instance
            $expo = \ExponentPhpSDK\Expo::normalSetup();
            
            // Subscribe the recipient to the server
            $expo->subscribe($channelName, $recipient);
            // Subscribe all tokens to the general notification
            $expo->subscribe($this->channels['default'], $recipient);
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

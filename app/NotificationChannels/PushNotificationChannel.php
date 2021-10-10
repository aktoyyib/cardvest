<?php

namespace App\NotificationChannels;

use Illuminate\Notifications\Notification;
use Log;

class PushNotificationChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        // Send notification to the $notifiable instance...
        $notifyData = $notification->toPushNotification($notifiable);
        // Check if the user is using the mobile application
        $user = $notifyData['user'];

        // If yes, send the notification to their mobile
        $pushNotificationID = $user->mobilePushId;

        if (is_null($pushNotificationID)) return;

        $pushNotificationToken = $pushNotificationID->token;

        // Instantiate Expo SDK
        $expo = \ExponentPhpSDK\Expo::normalSetup();

        // Build the notification data
        $notificationData = [
            'title'  => $notifyData['title'],
            'body' => $notifyData['description'],
            'data' => json_encode(array(
                'type' => $notifyData['notification_category'],
                'url' => $notifyData['url']
            ))
        ];

        try {
            $channelName = 'user_'.$user->id;

            // Subscribe the recipient to the server
            $expo->subscribe($channelName, $pushNotificationToken);

            // Notify an interest with a notification
            $expo->notify([$channelName], $notificationData);
            
        } catch (\Throwable $e) {
            report($e);
            throw \Exception($pushNotificationToken);
        }
    }
}
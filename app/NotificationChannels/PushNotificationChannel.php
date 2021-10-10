<?php

namespace App\NotificationChannels;

use Illuminate\Notifications\Notification;

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
        Log::warning("Push Notification send called");
    }
}
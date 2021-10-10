<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\NotificationChannels\PushNotificationChannel;

use App\Models\Transaction;
class OrderProcessed extends Notification
{
    use Queueable;

    protected $transaction;
    protected $user;
    protected $card;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
        $this->user = $transaction->user;
        $this->card = $transaction->card;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [PushNotificationChannel::class];
        // return ['database'];
        return ['database', 'mail', PushNotificationChannel::class]; // PushNotificationChannel::class
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->greeting('Hello '.$this->user->username.',')
                    ->line('Your gift card transaction has been processed by the admin.')
                    ->action('Check Transaction Status', route('transaction.show', $this->transaction))
                    ->line('Thank you for transacting with us!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //description, amount, status, payment_status, comment
            'description' => ucfirst($this->transaction->type)." $".$this->transaction->unit." ".$this->card->name." at ".$this->card->rate."/$",
            'type' => $this->transaction->type,
            'amount' => to_naira($this->transaction->amount),
            'status' => $this->transaction->status,
            'remark' => $this->transaction->admin_comment
        ];
    }

    /**
     * Get the push notification representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toPushNotification($notifiable)
    {
        $appDeepLink = "cardvest://transactions/".$this->transaction->reference;

        return [
            // description, amount, status, payment_status, comment, title
            'description' => ucfirst($this->transaction->type)." $".$this->transaction->unit." ".$this->card->name." at ".$this->card->rate."/$",
            'status' => $this->transaction->status,
            'user' => $this->user,
            'title' => 'Giftcard Order Proceed',
            'url' => $appDeepLink,
            'notification_category' => 'transactional'
        ];
    }
}
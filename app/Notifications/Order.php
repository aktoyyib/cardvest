<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use App\Models\Transaction;
use Illuminate\Notifications\Messages\SlackMessage;

class Order extends Notification
{
    use Queueable;

    protected $transaction;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Transaction $transaction)
    {
        $this->transaction = $transaction;
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
        return ['database', 'slack'];
        // return ['database', 'mail', 'slack'];
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
                    ->subject('✅ Transaction Notification - Cardvest')
                    ->greeting('Business Alert 💢!')
                    ->line(ucfirst($this->transaction->type)." $".$this->transaction->unit." ".$this->card->name." at ".$this->card->rate."/$")
                    ->line('You have a new transaction to attend to!')
                    ->action('View Transaction', route('transactions.show', $this->transaction))
                    ->line('Thank you!');
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
            'description' => ucfirst($this->transaction->type)." $".$this->transaction->unit." ".$this->card->name." at ".$this->card->rate."/$",
            'type' => $this->transaction->type,
            'amount' => to_naira($this->transaction->amount),
            'status' => $this->transaction->status,
            'payment' => $this->transaction->payment_status,
            'username' => $this->transaction->user->username,
        ];
    }

    /**
     * Get the Slack representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\SlackMessage
     */
    public function toSlack($notifiable)
    {
        $transaction  = $this->transaction;
        $card  = $this->card;

        return (new SlackMessage)
                ->success()
                ->content('A new order has been placed on CARDVEST!')
                ->attachment(function ($attachment) use ($transaction, $card) {
                    $attachment->title('Transaction '.$transaction->reference , route('transactions.show', $transaction))
                               ->fields([
                                    'Title' => ucfirst($transaction->type)." $".$transaction->unit." ".$card->name." at ".$card->rate."/$",
                                    'Amount' => 'NGN '.to_naira($transaction->amount),
                                ]);
                });
    }
}
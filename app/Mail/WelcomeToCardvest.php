<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\User;
use Log;

class WelcomeToCardvest extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        Log::info('Sending email');
        return $this->view('email.welcome')
                    ->with([
                        'username' => $this->user->username,
                    ]);
    }
}
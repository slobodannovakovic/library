<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;

class ResetPasswordNotification extends ResetPasswordBase implements ShouldQueue
{
    use Queueable;

    public function __construct(public $token, public $frontendUrl)
    {
        parent::__construct($token);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $tempUrl = URL::temporarySignedRoute(
            'password.reset', 
            now()->addMinutes(60), 
            ['email' => $notifiable->email, 'token' => $this->token]
        );

        $url = str_replace(
            [config('app.url'), '/api'], 
            [$this->frontendUrl, ''], 
            $tempUrl
        );

        return (new MailMessage)
            ->subject('Reset Your Password')
            ->line('Click the button below to reset your password.')
            ->action('Reset Password', $url)
            ->line('If you did not request a password reset, no further action is required.');
    }
}

<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminResetPassword extends Notification
{
    use Queueable;

    public $token;

    /**
     * Create a new notification instance.
     * AdminResetPassword constructor.
     * @param $token
     * @return void
     */
    public function __construct($token){
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable){
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable){
        return (new MailMessage)
            ->subject('Password reset request')
            ->action('Reset Password', url('admin/password/reset', $this->token))
            ->line('If the above link is not available, copy and paste the following URL into your browser:')
            ->line(url('admin/password/reset', $this->token))
            ->line("If you have not requested a new password or received this notification in error, you can ignore this email and your password will not be changed.")
            ->template('default')
            ->markdown('mail.notification');
    }

    /**
     * Get the array representation of the notification.
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable){
        return [
            //
        ];
    }

}

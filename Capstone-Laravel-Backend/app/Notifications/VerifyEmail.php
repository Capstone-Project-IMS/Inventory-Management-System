<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends VerifyEmailBase
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Verify Email Address')
            ->greeting($this->getGreeting($notifiable))
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email Address', $this->verificationUrl($notifiable))
            ->salutation('Thank you for using our application!<br>- Jacob, Montez, and Chris')
            ->line('If you did not create an account, no further action is required.');
    }

    private function getGreeting($notifiable)
    {
        switch ($notifiable->userType->role) {
            case 'customer':
                return 'Thank you for becoming a customer, ' . $notifiable->full_name;
            case 'vendor':
                return 'Thank you for partnering with us, ' . $notifiable->full_name;
            case 'employee':
                return 'Thank you for joining our team, ' . $notifiable->first_name;
            default:
                return 'Hello, ' . $notifiable->first_name;
        }
    } 
    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
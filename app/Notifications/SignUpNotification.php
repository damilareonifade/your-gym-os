<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignUpNotification extends Notification implements ShouldQueue
{
    use Queueable;
    protected $user;
    protected $link;
    protected $email;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $name, string $resetLink, string $email)
    {
        $this->user = $name;
        $this->email = $email;
        $this->link = $resetLink;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Gymno: Welcome Message')
            ->greeting('Dear ' . $this->user)
            ->line('We at Gymno Technologies seize this opportunity to thank you for choosing Gymno as your fitness and gym management application. We are glad that you are giving us this opportunity to serve you and we greatly value your business. We promise to provide you with reliable and efficient service.')
            ->line('We hope this will be the beginning of a long term business relationship between us. Our success lies in our prompt, professional and personal attention that we provide to you and all our valued customers.')
            ->line('Your login details follows')
            ->line('Email: ' . $this->email)
            ->line("Click this link to set your password")
            ->action('Reset Password', $this->link)
            ->line('Thank you for choosing us!');
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
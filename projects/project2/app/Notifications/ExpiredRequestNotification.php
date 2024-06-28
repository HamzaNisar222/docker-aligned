<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ExpiredRequestNotification extends Notification
{
    use Queueable;

    public $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function via($notifiable)
    {
        return ['mail']; // Only send via email
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Expired Request Notification')
            ->line('Your request has expired and has been cancelled.')
            ->line('Details:')
            ->line('Request ID: '.$this->request->id)
            ->line('Service Name: '.$this->request->vendorServiceOffering->subservice->name)
            ->line('Required Date: '.$this->request->required_at)
            ->line('Please contact support for further assistance.');
    }
}

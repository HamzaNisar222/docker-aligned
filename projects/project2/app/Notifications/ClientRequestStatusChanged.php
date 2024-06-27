<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientRequestStatusChanged extends Notification
{
    use Queueable;

    protected $clientRequest;
    protected $statusMessage;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($clientRequest, $statusMessage)
    {
        $this->clientRequest = $clientRequest;
        $this->statusMessage = $statusMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
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
        ->line('Dear' . $this->clientRequest->client->name . 'Your request status has been updated.')
        ->line('Request ID: ' . $this->clientRequest->id)
        ->line('Vendor:' . $this->clientRequest->vendorServiceOffering->vendor->name)
        ->line('Service: ' . $this->clientRequest->vendorServiceOffering->subservice->name)
        ->line('Details: ' . $this->clientRequest->details)
        ->line('Required At: ' . $this->clientRequest->required_at)
        ->line('Status: ' . $this->statusMessage)
        ->line('payment_status' . $this->clientRequest->payment_status)
        ->line('Thank you for using our application!');
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
            //
        ];
    }
}

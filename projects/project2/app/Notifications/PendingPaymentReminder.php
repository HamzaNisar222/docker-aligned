<?php

namespace App\Notifications;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PendingPaymentReminder extends Notification
{
    use Queueable;
    protected $clientRequest;


    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientRequest)
    {
        $this->clientRequest = $clientRequest;
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
        $paymentStatus = $this->clientRequest->payment_status ? 'paid' : 'pending';

        return (new MailMessage)
                    ->line('Your request has been approved but the payment is still pending.')
                    ->line('Request ID: ' . $this->clientRequest->id)
                    ->line('Vendor: ' . $this->clientRequest->vendorServiceOffering->vendor->name)
                    ->line('Service: ' . $this->clientRequest->vendorServiceOffering->subservice->name)
                    ->line('Required At: ' . $this->clientRequest->required_at)
                    ->line('Status: ' . $this->clientRequest->status)
                    ->line('Payment Status: ' . $paymentStatus)
                    ->line('Please complete the payment at your earliest convenience.')
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

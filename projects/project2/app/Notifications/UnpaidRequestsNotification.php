<?php

namespace App\Notifications;

use App\Models\ClientRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UnpaidRequestsNotification extends Notification
{
    use Queueable;

    public $approvedRequest;
    public $pendingRequests;

    public function __construct(ClientRequest $approvedRequest, $pendingRequests)
    {
        $this->approvedRequest = $approvedRequest;
        $this->pendingRequests = $pendingRequests;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Unpaid Requests Notification')
                    ->line('Dear Vendor,')
                    ->line('You have unpaid approved requests. Please review the details below:')
                    ->line('Approved Request Details:')
                    ->line('Request ID: ' . $this->approvedRequest->id)
                    ->line('Request Details: ' . $this->approvedRequest->details)
                    ->line('Pending Requests for Same Date:')
                    ->line('Pending Requests: ' . $this->pendingRequests->pluck('id')->implode(', '))
                    ->action('View Requests', url('/'))
                    ->line('Thank you for your attention.');
    }
}

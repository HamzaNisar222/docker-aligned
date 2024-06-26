<?php

namespace App\Mail;

use App\Models\User;
use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public $clientService;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientService)
    {
        $this->clientService = $clientService;
    }

    public function build() {
        return $this->subject('Service Registration Request')
                    ->view('emails.client-registration'); // Correct view name
    }
}

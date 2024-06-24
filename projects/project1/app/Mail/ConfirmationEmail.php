<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmationEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $confirmationUrl;

    public function __construct($confirmationUrl)
    {
        $this->confirmationUrl = $confirmationUrl;
    }

    public function build()
    {
        return $this->subject('Confirm Your Email')
                    ->view('emails.confirmation', ['confirmationUrl' => $this->confirmationUrl]);
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Confirm Your Email'
        );
    }

    public function content()
    {
        return new Content(
            view: 'emails.confirmation'
        );
    }
}

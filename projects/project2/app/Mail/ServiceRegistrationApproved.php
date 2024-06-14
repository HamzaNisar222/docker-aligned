<?php

namespace App\Mail;

use App\Models\VendorServiceRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ServiceRegistrationApproved extends Mailable
{
    use Queueable, SerializesModels;

    public $registration;

    /**
     * Create a new message instance.
     *
     * @param  VendorServiceRegistration  $registration
     * @return void
     */
    public function __construct(VendorServiceRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.service_registration_approved')
                    ->subject('Service Registration Approved');
    }
}

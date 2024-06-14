<?php

namespace App\Jobs;

use App\Mail\ServiceRegistrationApproved;
use App\Models\VendorServiceRegistration;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendServiceRegistrationApprovedMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $registration;

    /**
     * Create a new job instance.
     *
     * @param VendorServiceRegistration $registration
     */
    public function __construct(VendorServiceRegistration $registration)
    {
        $this->registration = $registration;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->registration->user->email)->send(new ServiceRegistrationApproved($this->registration));
    }
}

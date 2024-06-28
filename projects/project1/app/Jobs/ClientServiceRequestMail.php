<?php

namespace App\Jobs;

use App\Models\User;
use App\Mail\ClientMail;
use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class ClientServiceRequestMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientService;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientService)
    {
        $this->clientService = $clientService;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // get the mail from client_request relation with vendor for which servicew the client ask for
        $email=$this->clientService->vendorServiceOffering->vendor->email;
        Mail::to($email)->send(new ClientMail($this->clientService));
    }
}

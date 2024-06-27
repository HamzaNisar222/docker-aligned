<?php

namespace App\Jobs;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\PendingPaymentReminder;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendPendingPaymentReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $clientRequests = ClientRequest::where('status', 'approved')
            ->where('payment_status', false)
            ->get();

        foreach ($clientRequests as $clientRequest) {
            $clientRequest->client->notify(new PendingPaymentReminder($clientRequest));
        }
    }
}

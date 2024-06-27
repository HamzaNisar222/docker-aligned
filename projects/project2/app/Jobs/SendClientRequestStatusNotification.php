<?php

namespace App\Jobs;

use App\Models\ClientRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use App\Notifications\ClientRequestStatusChanged;

class SendClientRequestStatusNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $clientRequest;
    protected $statusMessage;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(ClientRequest $clientRequest, $statusMessage)
    {
        $this->clientRequest = $clientRequest;
        $this->statusMessage = $statusMessage;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->clientRequest->client->notify(new ClientRequestStatusChanged($this->clientRequest, $this->statusMessage));
    }
}

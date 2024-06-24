<?php

namespace App\Jobs;

use App\Models\ApiToken;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class RemoveExpiredTokens implements ShouldQueue
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
        try {
            ApiToken::where('expires_at', '<', now())->delete();
            echo 'expired token deleted';
        } catch (\Exception $e) {
            Log::error('RemoveExpiredTokens job failed: ' . $e->getMessage());
            // Handle or rethrow the exception if necessary
        }
    }
}

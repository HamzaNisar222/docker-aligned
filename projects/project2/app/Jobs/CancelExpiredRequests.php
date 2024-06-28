<?php
namespace App\Jobs;

use App\Models\ClientRequest;
use App\Notifications\ExpiredRequestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Carbon\Carbon;

class CancelExpiredRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $expiredRequests = ClientRequest::where(function ($query) {
            $query->where('status', 'pending')
                ->orWhere('status', 'approved');
        })
            ->where('payment_status', false)
            ->get();

        foreach ($expiredRequests as $request) {
            // Convert the 'required_at' string to a Carbon instance
            $requiredAt = Carbon::createFromFormat('j-n-Y', $request->required_at);

            // Compare the converted date with current date
            if ($requiredAt->lessThan(Carbon::now())) {
                $request->status = 'cancelled';
                $request->save();

                // Send notification to client
                $request->client->notify(new ExpiredRequestNotification($request));
            }
        }
    }
}


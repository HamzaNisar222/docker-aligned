<?php
namespace App\Jobs;

use App\Models\ClientRequest;
use App\Models\User;
use App\Notifications\UnpaidRequestsNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class NotifyUnpaidRequests implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $vendors = User::all();

        foreach ($vendors as $vendor) {
            $unpaidRequests = ClientRequest::whereHas('vendorServiceOffering', function ($query) use ($vendor) {
                $query->where('vendor_id', $vendor->id);
            })
            ->where('status', 'approved')
            ->where('payment_status', false)
            ->get();

            foreach ($unpaidRequests as $request) {
                $pendingRequests = ClientRequest::where('vendor_service_offering_id', $request->vendor_service_offering_id)
                    ->where('required_at', $request->required_at)
                    ->where('status', 'pending')
                    ->get();

                // Send notification to vendor
                $vendor->notify(new UnpaidRequestsNotification($request, $pendingRequests));
            }
        }
    }
}

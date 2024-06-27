<?php

namespace App\Models;

use App\Jobs\SendClientRequestStatusNotification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequestApproval extends ClientRequest
{
    use HasFactory;

    protected $table = "client_requests";
    // NOTE: all the functions here are used in VendorApprovalController
    // check request for approval
    public static function checkRequest($clientRequest)
    {

        if (!$clientRequest) {
            return response()->json(['error' => 'Request not found or you do not have access to it'], 404);
        }
        if ($clientRequest->status === 'approved') {
            return response()->json(['error' => 'Request is already approved'], 400);
        }
        // Check if another request for the same offer and date is already approved
        $existingApprovedRequest = parent::where('vendor_service_offering_id', $clientRequest->vendor_service_offering_id)
            ->where('required_at', $clientRequest->required_at)
            ->where('status', 'approved')
            ->exists();
        if ($existingApprovedRequest) {
            return response()->json(['error' => 'Another request for this offer on the same date is already approved'], 400);
        }
    }
    // Send mail for approval
    public static function sendapprovalmail($clientRequest)
    {
        // Approve the request
        $clientRequest->status = 'approved';
        $clientRequest->save();
        // Dispatch notification
        SendClientRequestStatusNotification::dispatch($clientRequest, 'approved');
    }
    // check request for rejection
    public static function checkRequestRejection($clientRequest)
    {
        if (!$clientRequest) {
            return response()->json(['error' => 'Request not fount or you dont have access to it'], 404);
        }
        if ($clientRequest->status === 'rejected') {
            return response()->json(['error' => 'Request already rejcted'], 400);
        }

    }
    // Send rejection mail
    public static function rejectionMail($clientRequest)
    {
        // Disapprove the request
        $clientRequest->status = 'rejected';
        $clientRequest->save();
        //  Dispatch the notification
        SendClientRequestStatusNotification::dispatch($clientRequest, 'rejected');
    }
    // Fetch requests for rejection
    public static function fetchForRejection($vendorId, $id)
    {
        // Fetch the request to be disapproved
        $clientRequest = ClientRequest::where('id', $id)
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->first();
        return $clientRequest;
    }
    // fetch requests for approval
    public static function fetchForApproval($vendorId, $id)
    {
        // Fetch the request to be approved
        $clientRequest = ClientRequest::where('id', $id)
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->first();
        return $clientRequest;
    }
}

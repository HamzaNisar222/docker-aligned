<?php

namespace App\Http\Controllers\Api;

use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Http\Controllers\Controller;

class VendorApprovalController extends Controller
{
    public function getClients(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch distinct clients who have made requests to this vendor's offerings
        $clientIds = ClientRequest::whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->distinct('client_id')->pluck('client_id');

          // Step 2: Fetch all clients with the retrieved client_ids
          $clients = Client::whereIn('id', $clientIds)->get();

        return response()->json($clients);
    }

    public function getAllRequests(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch all requests for the vendor's offers
        $requests = ClientRequest::whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->get();

        return response()->json($requests);
    }

    public function getPendingRequests(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch pending client requests for this vendor's offerings
        $pendingRequests = ClientRequest::where('status', 'pending')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();

        return response()->json($pendingRequests);
    }

    public function getApprovedRequests(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch approved client requests for this vendor's offerings
        $approvedRequests = ClientRequest::where('status', 'approved')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();

        return response()->json($approvedRequests);
    }

    public function getRejectedRequests(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch rejected client requests for this vendor's offerings
        $rejectedRequests = ClientRequest::where('status', 'rejected')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();

        return response()->json($rejectedRequests);
    }

    public function approveRequest(Request $request, $id)
    {
        $vendorId = $request->user->id;

        // Fetch the request to be approved
        $clientRequest = ClientRequest::where('id', $id)
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->first();
            if(! $clientRequest) {
                return response()->json(['error' => 'Request not found or you do not have access to it'], 404);
            }
            if ($clientRequest->status === 'approved') {
                return response()->json(['error' => 'Request is already approved'], 400);
            }

        // Approve the request
        $clientRequest->status = 'approved';
        $clientRequest->save();

        return response()->json(['message' => 'Request approved successfully']);
    }

    public function disapproveRequest(Request $request, $id)
    {
        $vendorId = $request->user()->id;

        // Fetch the request to be disapproved
        $clientRequest = ClientRequest::where('id', $id)
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->first();
            if(! $clientRequest) {
                return response()->json(['error'=> 'Request not fount or you dont have access to it'], 404);
            }
            if ($clientRequest->status === 'rejected') {
                return response()->json(['error'=> 'Request already rejcted'], 400);
            }

        // Disapprove the request
        $clientRequest->status = 'rejected';
        $clientRequest->save();

        return response()->json(['message' => 'Request disapproved successfully']);
    }
}

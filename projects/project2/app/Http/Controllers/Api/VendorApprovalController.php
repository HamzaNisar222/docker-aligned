<?php

namespace App\Http\Controllers\Api;

use App\Models\RequestApproval;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Http\Controllers\Controller;

class VendorApprovalController extends Controller
{
    // Get all vendor clients
    public function getClients(Request $request)
    {
        $vendorId = $request->user->id;
        // Fetch client Ids
        $clientIds = ClientRequest::clients($vendorId);
        // Step 2: Fetch all clients with the retrieved client_ids
        $clients = Client::whereIn('id', $clientIds)->get();
        return response()->json($clients);
    }
    // Get all requests
    public function getAllRequests(Request $request)
    {
        $vendorId = $request->user->id;

        // Fetch all requests for the vendor's offers
        $requests = ClientRequest::whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->get();

        return response()->json($requests);
    }
    // Get pending requests
    public function getPendingRequests(Request $request)
    {
        $vendorId = $request->user->id;
        // call to function for getting pending requests
        $pendingRequests = ClientRequest::pending($vendorId);
        return response()->json($pendingRequests);
    }
    // Get approved requests
    public function getApprovedRequests(Request $request)
    {
        $vendorId = $request->user->id;
        //    Call to function for getting approved
        $approvedRequests = ClientRequest::approved($vendorId);
        return response()->json($approvedRequests);
    }
    // Get rejected requests
    public function getRejectedRequests(Request $request)
    {
        $vendorId = $request->user->id;
        // Call to function for getting rejected requests
        $rejectedRequests = ClientRequest::rejected($vendorId);
        return response()->json($rejectedRequests);
    }
    // Approve a Request
    public function approveRequest(Request $request, $id)
    {
        $vendorId = $request->user->id;
        // call to function for fetching for approval
        $clientRequest = RequestApproval::fetchForApproval($vendorId, $id);
        // Check Request
        $check = RequestApproval::checkRequest($clientRequest);
        if ($check) {
            return $check;
        }

        //  send notification
        RequestApproval::sendapprovalmail($clientRequest);


        return response()->json(['message' => 'Request approved successfully']);
    }
    // Reject a request
    public function disapproveRequest(Request $request, $id)
    {
        $vendorId = $request->user->id;
        // Fetch Request for rejection
        $clientRequest = RequestApproval::fetchForRejection($vendorId, $id);
        $check = RequestApproval::checkRequestRejection($clientRequest);
        if ($check) {
            return $check;
        }
        // update status and send rejection mail
        RequestApproval::rejectionMail($clientRequest);

        return response()->json(['message' => 'Request disapproved successfully']);
    }
}

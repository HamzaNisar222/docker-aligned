<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Http\Controllers\Controller;
use App\Models\VendorServiceOffering;
use App\Jobs\ClientServiceRequestMail;
use App\Models\Client;

class ClientServiceController extends Controller
{
    //client Request for the service which is offered by vendor
    public function store(Request $request) {
        $vendorOffer = VendorServiceOffering::find($request->vendor_service_offering_id);
        if (!$vendorOffer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Service'
            ], 404);
        }
        // Check if the client requested service is already exists
        $exist = ClientRequest::requestExists($request);
        if (!$exist) {
            return response()->json([
                "status" => "error",
                 "message" => "Request already exists"
            ], 409);
        }
        // if no service requested find then call the model and save the client requested service
        $clientService = ClientRequest::createService($request);
        $clientService = ClientRequest::find($clientService->id);

        // here dispatch the the mail to the request client.
        ClientServiceRequestMail::dispatch($clientService);
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Register the Service wait for vendor Approvel',
            'request' => $clientService,
        ], 201);
    }

    // Client getting all pending Service Request that is associated with him
    public function pending(Request $request) {
        $clientId = $request->user->id;
        //get the id from request
        $clients = ClientRequest::where('client_id', $clientId)->where('status', 'pending')->get();
        if ($clients->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Pending Service'
            ], 404);
        }
        return response()->json([
            'data' => $clients,
        ], 201);

    }

    // Client getting all this approved request that is associated with him
    public function approved(Request $request) {
        $clientId = $request->user->id;
        // get the id from request
        $clients = ClientRequest::where('client_id', $clientId)->where('status', 'approved')->get();
        if ($clients->isEmpty()) {
            return response()->json([
                'status' => 'error',
                'message' => 'No Approved Service'
            ], 404);
        }
        return response()->json([
            'data' => $clients,
        ], 201);
    }

}

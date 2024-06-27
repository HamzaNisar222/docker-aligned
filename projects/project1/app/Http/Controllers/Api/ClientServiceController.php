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
    public function store(Request $request) {
        $vendorOffer = VendorServiceOffering::find($request->vendor_service_offering_id);
        if (!$vendorOffer) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid Service'
            ], 404);
        }
        $exist = ClientRequest::requestExists($request);
        if (!$exist) {
            return response()->json([
                "status" => "error",
                 "message" => "Request already exists"
            ], 409);
        }
        $clientService = ClientRequest::createService($request);
        $clientService = ClientRequest::find($clientService->id);
        ClientServiceRequestMail::dispatch($clientService);
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully Register the Service wait for vendor Approvel',
            'request' => $clientService,
        ], 201);
    }

    public function pending(Request $request) {
        $clientId = $request->user->id;
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

    public function approved(Request $request) {
        $clientId = $request->user->id;
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

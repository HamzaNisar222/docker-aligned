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
        $clients = ClientRequest::where('client_id', $clientId)->where('status', 'pending')->find();
        dd($clients);

    }

    public function approved(Request $request) {

    }

}

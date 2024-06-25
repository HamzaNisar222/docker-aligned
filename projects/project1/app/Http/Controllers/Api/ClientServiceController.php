<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Http\Controllers\Controller;

class ClientServiceController extends Controller
{
    public function store(Request $request)
    {
        
        $exist = ClientRequest::requestExists($request);
        if (!$exist) {
            return response()->json([
                "status" => "error",
                "message" => "Request already exists"
            ]);
        }
        // Call to function from Clientrequest Model
        $clientService = ClientRequest::createService($request);
        return response()->json([
            "status"=> "success",
            'message' => 'Successfully Register the Service wait for vendor Approvel',
            'Request'=> $clientService,
        ]);
    }
}

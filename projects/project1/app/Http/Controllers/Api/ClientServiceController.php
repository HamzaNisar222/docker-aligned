<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\ClientRequest;
use App\Http\Controllers\Controller;

class ClientServiceController extends Controller
{
    public function store(Request $request) {
        $clientService = ClientRequest::createService($request);
        return response()->json([
            'message' => 'Successfully Register the Service wait for vendor Approvel'
        ]);
    }
}

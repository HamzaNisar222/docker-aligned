<?php

namespace App\Http\Controllers;

use App\Helpers\HttpClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VendorController extends Controller
{
    protected $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getAvailableServices()
    {
        // This will communicate with our 2nd project to get the route to get all services
        $url = env('API_BASE_URL') . '/available-services';
        $response = $this->httpClient->sendGetRequest($url);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch available services'], 500);
        }

        return response()->json($response);
    }

    // This will communicate with our 2nd project to get the route of specific vendor services
    public function getVendorSpecificOfferings($vendorId)
    {
        $url = env('API_BASE_URL') . "/vendor-offerings/{$vendorId}";
        $response = $this->httpClient->sendGetRequest($url);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch vendor specific offerings'], 500);
        }

        return response()->json($response);
    }
}

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
        $url = env('API_BASE_URL') . '/available-services';
        $response = $this->httpClient->sendGetRequest($url);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch available services'], 500);
        }

        return response()->json($response);
    }

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

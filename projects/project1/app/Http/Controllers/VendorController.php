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

    public function getVendorOfferings()
    {
        $url = env('PROJECT2_API_URL') . '/api/vendor-offerings';
        $headers = ['Authorization' => 'Bearer ' . auth()->user()->api_token];

        $response = $this->httpClient->sendGetRequest($url, $headers);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch vendor offerings'], 500);
        }

        return response()->json($response);
    }

    public function getAvailableServices()
    {
        $url = 'http://localhost:8082/api/available-services'; // Replace with appropriate URL
        $response = $this->httpClient->sendGetRequest($url);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch available services'], 500);
        }

        return response()->json($response);
    }

    public function getVendorSpecificOfferings($vendorId)
    {
        $url = env('PROJECT2_API_URL') . "/api/vendor-offerings/{$vendorId}";
        $response = $this->httpClient->sendGetRequest($url);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to fetch vendor specific offerings'], 500);
        }

        return response()->json($response);
    }

    public function addServiceOffer(Request $request)
    {
        $url = env('PROJECT2_API_URL') . '/api/add/service-offer';
        $headers = ['Authorization' => 'Bearer ' . auth()->user()->api_token];
        $body = $request->all();

        $response = $this->httpClient->sendPostRequest($url, $headers, $body);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to add service offer'], 500);
        }

        return response()->json($response);
    }

    public function updateServiceOffer(Request $request, $id)
    {
        $url = env('PROJECT2_API_URL') . "/api/service-offers/{$id}";
        $headers = ['Authorization' => 'Bearer ' . auth()->user()->api_token];
        $body = $request->all();

        $response = $this->httpClient->sendPostRequest($url, $headers, $body);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to update service offer'], 500);
        }

        return response()->json($response);
    }

    public function deleteServiceOffer($id)
    {
        $url = env('PROJECT2_API_URL') . "/api/service-offer/{$id}";
        $headers = ['Authorization' => 'Bearer ' . auth()->user()->api_token];

        $response = $this->httpClient->sendPostRequest($url, $headers);

        if (isset($response['error'])) {
            return response()->json(['error' => 'Unable to delete service offer'], 500);
        }

        return response()->json($response);
    }
}

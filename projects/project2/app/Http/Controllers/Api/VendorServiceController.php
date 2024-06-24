<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorServiceOffering;
use Illuminate\Support\Facades\Response;
use App\Models\VendorServiceRegistration;
use App\Http\Resources\RequestServiceCollection;

class VendorServiceController extends Controller
{

    public function pending(Request $request)
    {
        $registrations = VendorServiceRegistration::pending($request);
        return new RequestServiceCollection($registrations);
    }

    public function approved(Request $request)
    {
        $registrations = VendorServiceRegistration::approved($request);
        return new RequestServiceCollection($registrations);
    }

    //  Add vendor service offering
    public function addServiceOffer(Request $request)
    {
        // Call to function from VendorServiceOffering
        $offering = VendorServiceOffering::createOffer($request);
        return response()->json(['message' => 'Subservice offering added successfully', 'offering' => $offering], 201);
    }

    public static function updateServiceOffer(Request $request, $id)
    {
        // Call to function in vendor service offering
        $offering = VendorServiceOffering::updateOffer($request, $id);
        if ($offering) {
            return Response::success('Offer Updated Successfully');
        }
        return Response::error('Offer not found', 404);

    }
    // Delete Offer
    public static function deleteServiceOffer(Request $request, $id)
    {
        // Call to function in vendor service offering
        $offering = VendorServiceOffering::deleteOffer($request, $id);
        if ($offering) {
            return Response::success('Offer deleted Successfully', 203);
        }
        return Response::error('Offer not found', 404);
    }

    public function getVendorOfferings(Request $request)
    {
        $offerings = VendorServiceOffering::where('vendor_id', $request->user->id)->get();

        return response()->json(['offerings' => $offerings], 200);
    }


    public function getAvailableServices()
    {
        $offerings = VendorServiceOffering::with('subservice.service')->get();

        return response()->json(['offerings' => $offerings], 200);
    }


    public function getVendorSpecificOfferings($vendorId)
    {
        $offerings = VendorServiceOffering::where('vendor_id', $vendorId)->with('subservice.service')->get();
        if(count($offerings) === 0) {
            return response()->json(['error'=> 'no Offerings found'],404);
        }
        return response()->json(['offerings' => $offerings], 200);
    }
}

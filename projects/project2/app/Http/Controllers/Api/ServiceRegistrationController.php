<?php
namespace App\Http\Controllers\api;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Response;
use App\Models\VendorServiceRegistration;
use App\Http\Requests\VendorServiceRequest;
use App\Http\Resources\RequestServiceResource;

class ServiceRegistrationController extends Controller
{
    public function index()
    {
    }

    public function create(VendorServiceRequest $request) {
        $existedRegistration = VendorServiceRegistration::existedRegistration($request);
        if ($existedRegistration) {
            return Response::error('Service Registration already exists either Pending or Approved', 409);
        }
        $registrations = VendorServiceRegistration::createRegistration($request);
        return new RequestServiceResource($registrations);
        // return Response::success($registration, "Successfully Register the Request", 200);
    }
    /**
     * Approve a service registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function approve($id)
    {
        $registration = VendorServiceRegistration::findOrFail($id);
        // call to function from vendor service registration model
        if (!$registration->approve()) {
            return response()->json(['message' => 'Service Registration is already approved'], 400);
        }
        return response()->json(['message' => 'Service Registration approved successfully'], 200);
    }

    /**
     * Reject a service registration.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function reject($id)
    {
        $registration = VendorServiceRegistration::findOrFail($id);
        // call to function from vendor service registration model
        if (!$registration->reject()) {
            return response()->json(['message' => 'Service Registration is already approved or rejected'], 400);
        }
        return response()->json(['message' => 'Service Registration rejected successfully'], 200);
    }
}

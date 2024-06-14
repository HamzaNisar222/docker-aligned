<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorServiceRegistration;
use App\Http\Resources\RequestServiceCollection;

class VendorServiceController extends Controller
{
    public function pending() {
        $registrations = VendorServiceRegistration::pending();
        return new RequestServiceCollection($registrations);
        // return Response::success($registrations, "Successfully Retrived Pending Request", 201);
    }

    public function approved() {
        $registrations = VendorServiceRegistration::approved();
        return new RequestServiceCollection($registrations);
        // return Response::success($registrations, "Successfully Retrived Approved Request", 201);
    }
}

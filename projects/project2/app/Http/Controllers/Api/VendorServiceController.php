<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\VendorServiceRegistration;
use App\Http\Resources\RequestServiceCollection;

class VendorServiceController extends Controller
{

    public function pending(Request $request) {
        $registrations = VendorServiceRegistration::pending($request->all());
        return new RequestServiceCollection($registrations);
    }

    public function approved(Request $request) {
        $registrations = VendorServiceRegistration::approved($request->all());
        return new RequestServiceCollection($registrations);
    }
}

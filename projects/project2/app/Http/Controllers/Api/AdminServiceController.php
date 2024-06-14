<?php

namespace App\Http\Controllers\Api;

use App\Models\VendorServiceRegistration;
use App\Http\Controllers\Controller;

class AdminServiceController extends Controller
{
    /**
     * Get all pending service registration requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function pending()
    {
        $registrations = VendorServiceRegistration::where('status', 'pending')->get();

        return response()->json(['registrations' => $registrations], 200);
    }

    /**
     * Get all approved service registration requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approved()
    {
        $registrations = VendorServiceRegistration::where('status', 'approved')->get();

        return response()->json(['registrations' => $registrations], 200);
    }

    /**
     * Get all rejected service registration requests.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function rejected()
    {
        $registrations = VendorServiceRegistration::where('status', 'rejected')->get();

        return response()->json(['registrations' => $registrations], 200);
    }

    /**
     * Get all pending service registration requests of a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userPending($userId)
    {
        $registrations = VendorServiceRegistration::where('vendor_id', $userId)
            ->where('status', 'pending')
            ->get();

        return response()->json(['registrations' => $registrations], 200);
    }

    /**
     * Get all approved service registration requests of a specific user.
     *
     * @param int $userId
     * @return \Illuminate\Http\JsonResponse
     */
    public function userApproved($userId)
    {
        $registrations = VendorServiceRegistration::where('vendor_id', $userId)
            ->where('status', 'approved')
            ->get();

        return response()->json(['registrations' => $registrations], 200);
    }
}

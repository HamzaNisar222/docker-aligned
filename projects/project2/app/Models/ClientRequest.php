<?php
// app/Models/ClientRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ClientRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'vendor_service_offering_id',
        'status',
        'details',
        'required_at',
    ];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function vendorServiceOffering()
    {
        return $this->belongsTo(VendorServiceOffering::class);
    }
    // Create Service
    public static function createService($request)
    {
        return self::create([
            'client_id' => $request->user->id,
            'vendor_service_offering_id' => $request->vendor_service_offering_id,
            'status' => 'pending',
            'details' => $request->details,
            'required_at' => $request->required_at,
        ]);
    }
    // Verify if request already exists
    public static function requestExists($request)
    {
        // Check if a request with the same client_id and vendor_service_offering_id already exists
        $existingRequest = self::where('client_id', $request->user->id)
            ->where('vendor_service_offering_id', $request->vendor_service_offering_id)
            ->first();

        if ($existingRequest) {
            return false; // Indicate that the request already exists
        }
        return true;
    }
    // Get approved for vendor
    public static function approved($vendorId)
    {
        // Fetch approved client requests for this vendor's offerings
        $approvedRequests = self::where('status', 'approved')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();
        return $approvedRequests;
    }
    // Get rejected for vendor
    public static function rejected($vendorId)
    {
        // Fetch rejected client requests for this vendor's offerings
        $rejectedRequests = ClientRequest::where('status', 'rejected')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();
        return $rejectedRequests;
    }
    // Get pending requests for a vendor
    public static function pending($vendorId)
    {
        // Fetch pending client requests for this vendor's offerings
        $pendingRequests = ClientRequest::where('status', 'pending')
            ->whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
                $query->where('vendor_id', $vendorId);
            })
            ->get();
        return $pendingRequests;
    }
    // Get Clients of a vendor
    public static function clients($vendorId)
    {
        // Fetch distinct clients who have made requests to this vendor's offerings
        $clientIds = ClientRequest::whereHas('vendorServiceOffering', function ($query) use ($vendorId) {
            $query->where('vendor_id', $vendorId);
        })->distinct('client_id')->pluck('client_id');
        return $clientIds;
    }
}

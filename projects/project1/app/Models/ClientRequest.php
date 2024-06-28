<?php
// app/Models/ClientRequest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

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

    public static function checkClientRequest($clientRequest)
    {
        // this check if the request have pending status
        if ($clientRequest->status == 'pending') {
            return response()->json([
                'error' => 'Wait for the Vendor approvel for the service',
            ], 401);
        }
        //this check if the request have approved status and payemnt_staus true
        if ($clientRequest->status == 'approved' && $clientRequest->payment_status === true) {
            return response()->json([
                'error' => 'You have already Paid for this service',
            ], 409);
        }
        
    }
}

<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use App\Jobs\SendServiceRegistrationApprovedMail;
use App\Jobs\SendServiceRegistrationRejectedMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VendorServiceRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'vendor_id',
        'service_id',
        'document_path',
        'status',
    ];

    // VendorServiceRegistration model relation to the user which vendor have the registed the service
    public function user()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // VendorServiceRegistration model relation to the service which service belong to vendor.
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Check if the service registerd by the vendor is not being registerd again.
    public static function existedRegistration($request)
    {
        return self::where('vendor_id', $request->user->id)
            ->where('service_id', $request->service_id)
            ->whereIn('status', ['pending', 'approved'])->exists();
    }

    // vendor send the request for the service registration. with the following data.
    public static function createRegistration($request)
    {
        $documentPath = $request->file('document_path')->store('documents');
        return self::create([
            'vendor_id' => $request->user->id,
            'service_id' => $request->service_id,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);
    }

    // vendor Get all his pending service request
    public static function pending($request)
    {
        return self::where('status', 'pending')->where('vendor_id', $request['user']['id'])->get();
    }

    // vendor Get all his approved service request.
    public static function approved($request)
    {
        return self::where('status', 'approved')->where('vendor_id', $request['user']['id'])->get();
    }

    // Verifiying vendor approval
    public static function isVendorApprovedForService($vendorId, $serviceId)
    {
        // Check for approval
        $Approved=self::where('vendor_id', $vendorId)
            ->where('service_id', $serviceId)
            ->where('status', 'approved')
            ->exists();
        if ($Approved) {
            return true;
        }
    }
    /**
     * Approve the service registration.
     *
     * @return bool
     */
    // Admin approving the service of vendor.
    public function approve()
    {
        if ($this->status === 'approved') {
            return false; // Already approved
        }
        // Update the request status
        $this->status = 'approved';
        $this->save();
        // Send email notification
        SendServiceRegistrationApprovedMail::dispatch($this);
        return true;
    }


    /**
     * Reject the service registration.
     *
     * @return bool
     */
    // Admin reject the service of vendor.
    public function reject()
    {
        if ($this->status === 'rejected') {
            return false; // Already rejected
        }
        // update the request status
        $this->status = 'rejected';
        $this->save();
        // Send email notification
        SendServiceRegistrationRejectedMail::dispatch($this);
        return true;
    }

}

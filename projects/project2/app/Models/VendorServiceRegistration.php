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

    public function user()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public static function existedRegistration($request)
    {
        return self::where('vendor_id', $request->user->id)
            ->where('service_id', $request->service_id)
            ->whereIn('status', ['pending', 'approved'])->exists();
    }

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

    public static function pending($request)
    {
        return self::where('status', 'pending')->where('vendor_id', $request['user']['id'])->get();
    }

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

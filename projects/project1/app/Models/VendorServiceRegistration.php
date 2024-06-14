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

    public static function registerService($request)
    {
        // Check for duplicate service registration
        if (
            self::where('vendor_id', $request->user->id)
                ->where('service_id', $request->service_id)
                ->exists()
        ) {
            return response()->json([
                'message' => 'Service Registration already exists for this user and service'
            ], 409);
        }
        $documentPath = self::storeDocument($request->file('document_path'));


        return self::create([
            'vendor_id' => $request->user->id, // Use request->user() to get the authenticated user
            'service_id' => $request->service_id,
            'document_path' => $documentPath,
            'status' => 'pending',
        ]);
    }

    private static function storeDocument($file)
    {
        // Generate a unique file name and move the file
        $uniqueFileName = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('request_documents'), $uniqueFileName);

        // Return the relative path to the file
        return 'request_documents/' . $uniqueFileName;
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
        $this->status = 'rejected';
        $this->save();

        // Send email notification
        SendServiceRegistrationRejectedMail::dispatch($this);
        return true;
    }

}

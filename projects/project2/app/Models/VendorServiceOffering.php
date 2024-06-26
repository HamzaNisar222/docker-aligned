<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VendorServiceOffering extends Model
{
    use HasFactory;
    protected $fillable = [
        'vendor_id',
        'subservice_id',
        'price',
        'time_slot',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function subservice()
    {
        return $this->belongsTo(Subservice::class);
    }

    // 1 Create Offer Used in Vendor service controller
    public static function createOffer($request)
    {
        $offering = static::create([
            'vendor_id' => $request->user->id,
            'subservice_id' => $request->subservice_id,
            'price' => $request->price,
            'time_slot' => $request->time_slot,
        ]);
        return $offering;
    }

    // 2 Verify Approval
    public static function isApproved($request)
    {
        // Find the subservice
        $subservice = Subservice::find($request->subservice_id);
        if ($subservice == null) {
            return response()->json(['error' => 'Subservice not found'], 403);
        }
        // Check if the vendor has approval for the main service of the subservice
        $mainServiceId = $subservice->service_id;
        $vendorServiceRegistration = VendorServiceRegistration::where('vendor_id', $request->user->id)
            ->where('service_id', $mainServiceId)
            ->where('status', 'approved')
            ->first();
        if (!$vendorServiceRegistration) {
            return response()->json(['error' => 'Vendor is not approved for the main service of this subservice'], 403);
        }

        // Check if the vendor offering already exists
        $existingOffer = static::where('vendor_id', $request->user->id)
            ->where('subservice_id', $request->subservice_id)
            ->first();

        if ($existingOffer) {
            return response()->json(['error' => 'Vendor offer for this subservice already exists'], 409);
        }

        return true; // Approved and no duplicate offer
    }

    // 3 Update Offer used in vendor service controller
    public static function updateOffer($request, $id)
    {
        // Find offer
        $offering = static::findOrFail($id);
        // confirm the vendor is the owner of the offer
        if ($offering->vendor_id !== $request->user->id) {
            return false;
        }
        // update offer
        $offering->update($request->only('price', 'time_slot'));

        return $offering;

    }
    // 4 Delete Offer
    public static function deleteOffer($request, $id)
    {
        // Find offer
        $offering = static::findOrFail($id);
        // Verify vendor is the owner of the offer
        if ($offering->vendor_id !== $request->user->id) {
            return false;
        }
        // Delete Offer
        $offering->delete();
        return true;
    }
}

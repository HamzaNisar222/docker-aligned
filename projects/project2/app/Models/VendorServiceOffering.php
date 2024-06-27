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
        // Check if the requested user have any service offered in that time slot exist or not.
        if (static::timeSlotConflict($request->user->id, $request->time_slot)) {
            return response()->json([
                'error' => 'Time Conflict, You Already have Service Offered in this time slot.'
            ], 409);
        };
        $offering = static::create([
            'vendor_id' => $request->user->id,
            'subservice_id' => $request->subservice_id,
            'price' => $request->price,
            'time_slot' => $request->time_slot,
        ]);
        return $offering;
    }

    // Check time Slot Conflict
    public static function timeSlotConflict($vendorId, $timeSlot, $excludeId = null){
        [$start, $end] = static::timeSlotParse($timeSlot);

        if (!$start || !$end) {
            // Return conflict true if time slot parsing fails
            return true;
        }

        $query = static::where('vendor_id', $vendorId);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }
        $conflict = $query->get()->contains(function ($offering) use ($start, $end) {
            [$existedStart, $existedEnd] = static::timeSlotParse($offering->time_slot);
            return static::timeSlotOverlap($start, $end, $existedStart, $existedEnd);
        });
        return $conflict;
    }

    // Parse the Time Slot
    public static function timeSlotParse($timeSlot) {
        // Validate the time slot format and parse it
        if (!preg_match('/^\d{2}:\d{2}-\d{2}:\d{2}$/', $timeSlot)) {
            // Invalid format
            return [null, null];
        }
        // Assuming the time is 08:00-10:00
        [$start, $end] = explode('-', $timeSlot);
        $startTime = strtotime($start);
        $endTime = strtotime($end);

        // Check if strtotime returns false
        if ($startTime === false || $endTime === false) {
            return [null, null];
        }

        return [$startTime, $endTime];
    }

    // Check if Two time Slot Overlap
    public static function timeSlotOverlap($start1, $end1, $start2, $end2) {
        return $start1 < $end2 && $start2 < $end1;
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

        // Check if the requested user have any service offered in that time slot exist or not.
        if (static::timeSlotConflict($request->user->id, $request->time_slot, $id)) {
            return response()->json([
                'error' => 'Time Conflict, You Already have Service Offered in this time slot.'
            ], 409);
        };

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

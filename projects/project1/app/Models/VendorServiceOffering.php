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
        return $this->belongsTo(User::class, 'client_id');
    }


    // Create Offer Used in Vendor service controller
    public static function createOffer($request)
    {
        $offering=static::create([
            'vendor_id' => $request->user->id,
            'subservice_id' => $request->subservice_id,
            'price' => $request->price,
            'time_slot' => $request->time_slot,
        ]);
        return $offering;
    }

    // Update Offer used in vendor service controller
    public static function updateOffer($request,$id){
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
// Delete Offer
    public static function deleteOffer($request,$id){
        // Find offer
        $offering=static::findOrFail($id);
        // Verify vendor is the owner of the offer
        if ($offering->vendor_id !== $request->user->id){
            return false;
        }
        // Delete Offer
        $offering->delete();
        return true;
    }
}

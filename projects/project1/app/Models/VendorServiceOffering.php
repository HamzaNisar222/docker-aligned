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

    // VendorServiceOffers model relation to the vendor which vendor have the service offered.
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    // VendorServiceOffers model relation to the Subservice which belong to the Service offered by vendor.
    public function subservice() {
        return $this->belongsTo(Subservice::class);
    }

    // VendorServiceOffers model relation to the Client which client have make request to the vendor.
    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    // VendorServiceOffers model relation to the ClientRequest which service request is made to the vendor.
    public function clientRequest() {
        return $this->hasMany(ClientRequest::class, 'vendor_service_offering_id');
    }
}

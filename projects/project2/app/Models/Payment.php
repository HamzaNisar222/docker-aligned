<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'vendor_id', 'service_id', 'subservice_id', 'amount', 'payment_status', 'client_request_id',
    ];
    // protected $gaurded = [];

    // Payment model relation with Client
    public function client() {
        return $this->belongsTo(Client::class);
    }

    // Payment model relation with vendor
    public function vendor() {
        return $this->belongsTo(User::class);
    }

    // Payment model relation to the services which payemnt is make
    public function service() {
        return $this->belongsTo(Service::class);
    }

    // Payment model relation to the subservice which payment is make
    public function subservice() {
        return $this->belongsTo(Subservice::class);
    }

    // Payment model relation to the services which the receipt is generated
    public function receipt() {
        return $this->hasMany(Receipt::class);
    }

    // Payment model relation to the ClientRequest to which the payment is belong
    public function clientRequest() {
        return $this->belongsTo(ClientRequest::class);
    }

    // creating the pyament record with the following information.
    public static function createPayment($vendorServiceOffering, $clientRequest) {
        return self::create([
            'client_id' => $clientRequest->client_id,
            'vendor_id' => $vendorServiceOffering->vendor_id,
            'service_id' => $vendorServiceOffering->subservice->service_id, // Assuming subservice belongs to a service
            'subservice_id' => $vendorServiceOffering->subservice_id,
            'client_request_id' => $clientRequest->id,
            'amount' => $vendorServiceOffering->price,
            'payment_status' => 'pending',
        ]);
    }
}

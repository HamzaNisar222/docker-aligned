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

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function vendor() {
        return $this->belongsTo(User::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function subservice() {
        return $this->belongsTo(Subservice::class);
    }

    public function receipt() {
        return $this->hasMany(Receipt::class);
    }

    public function clientRequest() {
        return $this->belongsTo(ClientRequest::class);
    }

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

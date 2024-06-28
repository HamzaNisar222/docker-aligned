<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receipt extends Model
{
    use HasFactory;

    protected $fillable = [
        'payment_id', 'client_name', 'vendor_name', 'service_name', 'subservice_name', 'amount',
    ];

    // Receipt model relation to the payment model for which the receipt is belong
    public function payment() {
        return $this->belongsTo(Payment::class);
    }
}

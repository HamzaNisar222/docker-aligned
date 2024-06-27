<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id', 'vendor_id', 'service_id', 'subservice_id', 'amount', 'payment_status',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function vendor() {
        return $this->belongsTo(User::class);
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function subService() {
        return $this->belongsTo(Subservice::class);
    }

    public function receipt() {
        return $this->hasMany(Receipt::class);
    }
}

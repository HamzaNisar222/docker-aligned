<?php

namespace App\Models;

use App\Models\Service;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subservice extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'name',
        'description',
    ];

    // SubService model relation to the main Service model which service have the subservice
    public function service() {
        return $this->belongsTo(Service::class);
    }

    // SubService model relation to the payment model for which the payement is belong to the subservice
    public function payment() {
        return $this->hasMany(Payment::class);
    }
}

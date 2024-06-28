<?php

namespace App\Models;

use App\Models\Subservice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    // Service model relation to the many Subservice model
    public function subServices() {
        return $this->hasMany(Subservice::class);
    }

    // Service model relation to the payment model whuich payment belong to the service
    public function payment() {
        return $this->hasMany(Payment::class);
    }
}

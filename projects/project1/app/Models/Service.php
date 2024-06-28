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

    public function subServices() {
        return $this->hasMany(Subservice::class);
    }

    public function payment() {
        return $this->hasMany(Payment::class);
    }
}

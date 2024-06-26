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

    public function client(){
        return $this->belongsTo(Client::class,'client_id');
    }



}

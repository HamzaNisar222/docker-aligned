<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'token', 'ip_address', 'expires_at','tokenable_id', 'tokenable_type',
     ] ;

     public function tokenable()
     {
         return $this->morphTo();
     }

     

}

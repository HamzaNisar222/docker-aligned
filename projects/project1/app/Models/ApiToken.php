<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

     public static function createToken($tokenable, $ipAddress, $expiresIn = 1)
     {
         $token = Str::random(80);

         $apiToken = self::create([
             'tokenable_id' => $tokenable->id,
             'tokenable_type' => get_class($tokenable),
             'token' => $token,
             'ip_address' => $ipAddress,
             'expires_at' => now()->addHours($expiresIn),
         ]);

         return $apiToken;
     }

}

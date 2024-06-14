<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ApiToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'token', 'ip_address', 'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function createTokenForUser($user, $ipAddress, $expiresIn = 1)
    {
        $token = Str::random(80);

        $apiToken = self::create([
            'user_id' => $user->id,
            'token' => $token,
            'ip_address' => $ipAddress,
            'expires_at' => now()->addHours($expiresIn),
        ]);

        return $apiToken;
    }
}

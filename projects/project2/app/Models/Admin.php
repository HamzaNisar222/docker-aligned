<?php

namespace App\Models;

use App\Models\ApiToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Response;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'permissions', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'permissions'=>'array',
    ];

    protected $dates = [
        'deleted_at'
    ];

    public function apiTokens()
    {
        return $this->morphMany(ApiToken::class, 'tokenable');
    }

    // Admin authentication
    public static function authenticate($email,$password){
        $admin = static::where('email', $email)->first();

        // User not found
        if (!$admin) {
            return null;
        }
        // Account not active
        if (!$admin->status) {
            return null;
        }
        // Incorrect password
        if (!Hash::check($password, $admin->password)) {
            return null;
        }

        return $admin;
    }

    

}

<?php

namespace App\Models;

use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admin extends Model
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'role', 'permissions', 'status',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function hasAnyPermission($permissions)
    {
        if (is_array($permissions)) {
            return collect($permissions)->intersect($this->permissions)->isNotEmpty();
        }

        return $this->permissions && in_array($permissions, $this->permissions);
    }
    
    public function apiTokens()
    {
        return $this->morphMany(ApiToken::class, 'tokenable');
    }

    public static function authenticate($email,$password){
        $admin = static::where('email', $email)->first();

        if (!$admin) {
            return null; // User not found
        }

        if (!$admin->status) {
            echo "Email not verified";
            return null; // Account not active
        }

        if (!Hash::check($password, $admin->password)) {
            return null; // Incorrect password
        }

        return $admin;
    }

}

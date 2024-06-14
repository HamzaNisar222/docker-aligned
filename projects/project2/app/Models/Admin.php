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
        'name', 'email', 'password', 'role',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

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

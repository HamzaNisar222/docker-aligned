<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ApiToken;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use App\Models\VendorServiceRegistration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone_number',
        'address',
        'status',
        'confirmation_token',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // User model relation to the apiToken for which is associated with the user for authentication.
    public function apiTokens()
    {
        return $this->morphMany(ApiToken::class, 'tokenable');
    }

    // User model relation to the vendorService for the specific user service
    public function vendorService()
    {
        return $this->hasMany(VendorServiceRegistration::class);
    }

    // User model relation to the vendorServiceOffers for the specific user which service is approved and offerd by vendor.
    public function vendorServiceOfferings()
    {
        return $this->hasMany(VendorServiceOffering::class, 'vendor_id');
    }

    // User model relation to the payment model all the payment that belong to the specific user.
    public function payment() {
        return $this->hasMany(Payment::class);
    }

    // User is regististration is stored with the following information.
    public static function createUser($data)
    {
        return self::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'phone_number' => $data['phone_number'],
            'address' => $data['address'],
            'status' => 0, // Inactive
            'confirmation_token' => Str::random(60),
        ]);
    }
    // Authenticate user
    public static function authenticate($email, $password)
    {
        $user = static::where('email', $email)->first();
        if (!$user) {
            return null; // User not found
        }
        if (!$user->status) {
            return null; // Account not active
        }
        if (!Hash::check($password, $user->password)) {
            return null; // Incorrect password
        }

        return $user;
    }

    // admin can archive the specific user by id. which is done in project 2.
    public static function deleteUser($id)
    {
        $user = self::findOrFail($id);
        $user->delete();
        return response()->json(['message' => 'User deleted successfully'], 200);
    }


}

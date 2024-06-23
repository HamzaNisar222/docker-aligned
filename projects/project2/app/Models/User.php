<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\ApiToken;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Response;
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

    public function apiTokens()
    {
        return $this->morphMany(ApiToken::class, 'tokenable');
    }

    public function vendorService()
    {
        return $this->hasMany(VendorServiceRegistration::class);
    }
    public static function createUser($data){
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

    public static function authenticate($email,$password){
        $user = static::where('email', $email)->first();

        if (!$user) {
            return null; // User not found
        }

        if (!$user->status) {
            echo "Email not verified";
            return null; // Account not active
        }

        if (!Hash::check($password, $user->password)) {
            return null; // Incorrect password
        }

        return $user;
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function activateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == true) {
            return Response::error('User already activated', 401);
        }
        $user->status = true;
        $user->save();

        return response()->json(['message' => 'User activated successfully'], 200);
    }

    public function deactivateUser($id)
    {
        $user = User::findOrFail($id);
        if ($user->status == true) {
            $user->status = false;
            $user->save();
            return response()->json(['message' => 'User deactivated successfully'], 200);
        }
        return Response::error('user already deactivated', 401);


    }

}

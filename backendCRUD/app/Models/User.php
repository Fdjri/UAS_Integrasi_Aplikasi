<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'username',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Relasi ke customer profile
    public function customerProfile()
    {
        return $this->hasOne(CustomerProfile::class, 'user_id');
    }

    // Relasi ke service provider profile
    public function serviceProviderProfile()
    {
        return $this->hasOne(ServiceProviderProfile::class, 'user_id', 'id');
    }

    // Relasi ke layanan (services) yang dimiliki service provider
    public function services()
    {
        return $this->hasMany(Service::class, 'user_id', 'id');
    }

    // Relasi booking jika role customer
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'customer_id', 'id');
    }
}

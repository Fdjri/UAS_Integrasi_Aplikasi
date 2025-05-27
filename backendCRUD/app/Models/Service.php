<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $table = 'services';

    protected $primaryKey = 'service_id';

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'price',
        'photo',
        'service_type',     // kolom enum baru
        'service_address',  // kolom varchar baru
    ];

    // Relasi ke User (provider)
    public function provider()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Jika masih diperlukan relasi ke ServiceProviderProfile
    public function providerProfile()
    {
        return $this->belongsTo(ServiceProviderProfile::class, 'user_id', 'user_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'service_id', 'service_id');
    }
}

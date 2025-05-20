<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'bookings';

    protected $fillable = [
        'customer_id',
        'service_id',
        'booking_date',
        'status',
    ];

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id', 'id');
    }
}

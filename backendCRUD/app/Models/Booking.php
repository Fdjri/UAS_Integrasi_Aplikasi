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
        'booking_code',
        'notes',
        'cancel_reason',
        'check_in',
        'check_out',
        'updated_by',
    ];

    public $timestamps = true;

    /**
     * Relasi ke Customer (User)
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    /**
     * Relasi ke Service
     */
    public function service()
    {
        return $this->belongsTo(Service::class, 'service_id', 'service_id');
    }

    /**
     * Relasi ke Payment (one-to-one)
     */
    public function payment()
    {
        return $this->hasOne(Payment::class, 'booking_id', 'id');
    }
}

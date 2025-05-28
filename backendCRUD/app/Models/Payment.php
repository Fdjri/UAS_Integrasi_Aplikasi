<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    protected $fillable = [
        'booking_id',
        'payment_date',
        'amount',
        'payment_status',
        'method',
        'transaction_id',
        'payment_expiry',
        'paid_at',
        'gross_amount',
        'fee',
        'currency',
        'notes',
        'status_reason',
    ];

    public $timestamps = true;

    /**
     * Relasi ke Booking (many-to-one)
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    /**
     * Relasi ke Customer melalui Booking (hasOneThrough)
     */
    public function customer()
    {
        return $this->hasOneThrough(
            User::class,
            Booking::class,
            'id',           // Foreign key di tabel Booking (booking.id)
            'id',           // Foreign key di tabel User (user.id)
            'booking_id',   // Local key di tabel Payment (payment.booking_id)
            'customer_id'   // Local key di tabel Booking (booking.customer_id)
        );
    }
}

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
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id', 'id');
    }

    public function customer()
    {
        return $this->hasOneThrough(
            User::class,   
            Booking::class, 
            'id',           
            'id',           
            'booking_id',   
            'customer_id'  
        );
    }
}

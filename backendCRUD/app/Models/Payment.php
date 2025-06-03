<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    protected $primaryKey = 'payment_id';

    // Karena payment_id bigint auto-increment, set ini ke true
    public $incrementing = true;

    // Tipe primary key adalah integer (bigint)
    protected $keyType = 'int';

    protected $fillable = [
        // jangan isi payment_id manual karena auto-increment
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
}

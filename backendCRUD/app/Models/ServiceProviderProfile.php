<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceProviderProfile extends Model
{
    protected $table = 'service_provider_profiles';

    protected $fillable = [
        'user_id',
        'company_name',
        'business_phone',
        'business_address',
        'service_type',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

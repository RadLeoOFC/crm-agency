<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    protected $fillable = [
        'code',
        'discount_type',
        'discount_value',
        'currency',
        'max_uses',
        'max_uses_per_client',
        'starts_at',
        'ends_at',
        'min_order_amount',
        'applies_to',
        'platform_id',
        'price_list_id',
        'is_active',
        'is_stackable',
    ];

    public function priceLists()
    {
        return $this->belongsTo(PriceList::class);
    }

    public function platforms()
    {
        return $this->belongsTo(Platform::class);
    }

    public function redemptions()
    { 
        return $this->hasMany(PromoRedemption::class); 
    }

    public function bookings()
    { 
        return $this->hasMany(Booking::class); 
    }
}

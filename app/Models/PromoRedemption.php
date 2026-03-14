<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoRedemption extends Model
{
    protected $fillable = ['promo_code_id','client_id','booking_id','discount_amount','used_at'];

    public function promocode()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id'); 
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id'); 
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id'); 
    }
}

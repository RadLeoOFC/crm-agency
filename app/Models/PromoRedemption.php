<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PromoRedemption extends Model
{
    protected $fillable = ['promo_code_id','client_id','booking_id','discount_amount','used_at'];

    public function promo()
    {
        return $this->belongsTo(PromoCode::class, 'promo_code_id'); 
    }
}

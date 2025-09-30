<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','contact_person','email','phone','company',
        'vat_number','country','city','address','is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function promoRedemptions()
    {
        return $this->hasMany(PromoRedemption::class);
    }
}

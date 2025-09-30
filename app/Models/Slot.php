<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id',
        'price_list_id',
        'price_list_rule_id',
        'price_override_id',
        'starts_at',
        'ends_at',
        'price',
        'status',
        'capacity',
        'used_capacity',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'price'     => 'decimal:2',
        'capacity'  => 'integer',
        'used_capacity' => 'integer',
    ];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function priceList()
    {
        return $this->belongsTo(PriceList::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available' && $this->used_capacity < $this->capacity;
    }
}


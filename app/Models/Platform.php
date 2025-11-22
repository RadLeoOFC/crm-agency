<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'currency',
        'timezone',
        'is_active',
    ];

    public static $currencies = [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'BGN' => 'Bulgarian Lev'
    ];

    public function priceLists()
    {
        return $this->hasMany(PriceList::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

}

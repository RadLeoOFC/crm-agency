<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id','client_id','slot_id','starts_at','ends_at',
        'price','status','notes',
        'list_price','discount_amount','currency','promo_code_id', // NEW
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at'   => 'datetime',
        'price'     => 'decimal:2',
        'list_price'=> 'decimal:2',
        'discount_amount' => 'decimal:2',
    ];


    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}

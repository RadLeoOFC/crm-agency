<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'platform_id','client_id','slot_id','starts_at','ends_at',
        'price','status','notes'
    ];
}

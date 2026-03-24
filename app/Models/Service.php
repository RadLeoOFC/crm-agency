<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'base_price',
        'currency',
        'is_active',
    ];

    public static $currencies = [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'BGN' => 'Bulgarian Lev',
        'GBP' => 'British pound',
        'RUB' => 'Russian ruble',
    ];

    public function order_item() 
    {
        return $this->hasMany(OrderItem::class);
    }
}

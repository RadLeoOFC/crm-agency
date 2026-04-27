<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'order_id',
        'service_id',
        'qty',
        'price',
        'subtotal',
    ];

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }

    public function service() 
    {
        return $this->belongsTo(Service::class);
    }

    protected static function boot()
    {
        parent::boot();

        // Сработает при создании и обновлении записи
        static::saved(function ($order_item) {
            $order_item->order->countTotalAmount();
        });

        // Сработает при удалении записи
        static::deleted(function ($order_item) {
            $order_item->order->countTotalAmount();
        });
    }

}

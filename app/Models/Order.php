<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderItem;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'manager_id',
        'status',
        'total_amount',
    ];

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function manager() 
    {
        return $this->belongsTo(User::class);
    }

    public function order_item() 
    {
        return $this->hasMany(OrderItem::class);
    }

    public function countTotalAmount()
    {
        $total = $this->order_item()->sum('subtotal');

        $this->update(['total_amount' => $total]);
    }
}

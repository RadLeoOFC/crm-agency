<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'client_id',
        'order_id',
        'booking_id',
        'created_by',
        'currency',
        'amount_due',
        'status',
        'issued_at',
        'due_at',
        'sent_at',
        'paid_at',
        'voided_at',
        'public_token',
        'purpose',
        'notes',
        'meta',
    ];

    public function client() 
    {
        return $this->belongsTo(Client::class);
    }

    public function order() 
    {
        return $this->belongsTo(Order::class);
    }

    public function booking() 
    {
        return $this->belongsTo(Booking::class);
    }

    public function created_by() 
    {
        return $this->belongsTo(User::class);
    }

    public static $currencies = [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'BGN' => 'Bulgarian Lev',
        'RUB' => 'Russian Rubles',
        'GBP' => 'British pound',
    ];

    public function publish() {
        if (is_null($this->issued_at)) {
            $this->update(['issued_at' => now()]);
        }
    }

    public function send() {
        if (is_null($this->sent_at)) {
            $this->update(['sent_at' => now()]);
        }
    }
}

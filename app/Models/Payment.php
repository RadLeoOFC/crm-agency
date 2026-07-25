<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    //
    protected $fillable = [
        'invoice_id',
        'provider',
        'amount',
        'currency',
        'status',
        'provider',
        'transaction_ref',
        'processed_at',
        'raw_payload',
    ];

    public function invoice() 
    {
        return $this->belongsTo(Invoice::class);
    }
}

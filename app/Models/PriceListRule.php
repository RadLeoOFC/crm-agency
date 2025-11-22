<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceListRule extends Model
{
    protected $fillable = ['price_list_id','weekday','starts_at','ends_at','slot_price','capacity','is_active'];
    public function priceList()
    {
        return $this->belongsTo(PriceList::class); 
    }
}

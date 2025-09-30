<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceOverride extends Model
{
    protected $fillable = ['price_list_id','for_date','starts_at','ends_at','slot_price','capacity','is_active'];
    public function priceList()
    {
        return $this->belongsTo(PriceList::class); 
    }
}

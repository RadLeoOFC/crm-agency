<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceList extends Model
{
    protected $fillable = ['platform_id','name','currency','is_active','valid_from','valid_to','timezone','default_slot_duration'];

    public function platform()
    { 
        return $this->belongsTo(Platform::class); 
    }

    public function rules()
    { 
        return $this->hasMany(PriceListRule::class); 
    }

    public function overrides()
    { 
        return $this->hasMany(PriceOverride::class);
    }
}

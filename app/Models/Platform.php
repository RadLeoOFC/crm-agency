<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Platform extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'currency',
        'timezone',
        'is_active',
    ];

    public static $currencies = [
        'USD' => 'US Dollar',
        'EUR' => 'Euro',
        'BGN' => 'Bulgarian Lev'
    ];
}

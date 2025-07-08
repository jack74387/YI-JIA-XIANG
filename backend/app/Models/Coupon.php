<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'type', 'value', 'min_order', 'expires_at', 'usage_limit', 'used_count', 'description'
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
} 
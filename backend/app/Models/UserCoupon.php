<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCoupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'coupon_id', 'is_used', 'used_at', 'order_id'
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
    ];

    /**
     * 關聯用戶
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 關聯優惠券
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * 關聯訂單
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }
} 
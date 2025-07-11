<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'type', 'value', 'min_order', 'expires_at', 'usage_limit', 'used_count', 'description', 'is_active'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * 關聯訂單
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 關聯用戶優惠券使用記錄
     */
    public function userCoupons()
    {
        return $this->hasMany(UserCoupon::class);
    }

    /**
     * 檢查優惠券是否有效
     */
    public function isValid()
    {
        // 檢查是否啟用
        if (!$this->is_active) {
            return false;
        }

        // 檢查是否過期
        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        // 檢查使用次數限制
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    /**
     * 檢查用戶是否可以使用此優惠券
     */
    public function canBeUsedByUser($user, $orderAmount = 0)
    {
        // 檢查基本有效性
        if (!$this->isValid()) {
            return false;
        }

        // 檢查最低訂單金額
        if ($this->min_order && $orderAmount < $this->min_order) {
            return false;
        }

        // 檢查用戶是否已經使用過此優惠券
        $userCoupon = $this->userCoupons()->where('user_id', $user->id)->first();
        if ($userCoupon && $userCoupon->is_used) {
            return false;
        }

        return true;
    }

    /**
     * 計算折扣金額
     */
    public function calculateDiscount($orderAmount)
    {
        if ($this->type === 'percent') {
            return round($orderAmount * $this->value / 100);
        } else {
            return min($this->value, $orderAmount);
        }
    }

    /**
     * 取得折扣顯示文字
     */
    public function getDiscountTextAttribute()
    {
        if ($this->type === 'percent') {
            return $this->value . '%';
        } else {
            return 'NT$' . $this->value;
        }
    }

    /**
     * 取得狀態文字
     */
    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return '停用';
        }
        
        if ($this->expires_at && $this->expires_at->isPast()) {
            return '已過期';
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return '已用完';
        }
        
        return '啟用';
    }
} 
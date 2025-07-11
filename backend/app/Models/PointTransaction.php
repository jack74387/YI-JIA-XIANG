<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PointTransaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'type',
        'description',
        'order_id',
        'admin_id',
        'expires_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    /**
     * 關聯用戶
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 關聯訂單
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * 關聯管理員
     */
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * 取得類型顯示名稱
     */
    public function getTypeNameAttribute()
    {
        $types = [
            'earn' => '獲得',
            'spend' => '使用',
            'expire' => '過期',
            'adjust' => '調整',
        ];

        return $types[$this->type] ?? '未知';
    }

    /**
     * 取得類型顏色
     */
    public function getTypeColorAttribute()
    {
        $colors = [
            'earn' => 'text-green-600',
            'spend' => 'text-red-600',
            'expire' => 'text-gray-600',
        ];

        return $colors[$this->type] ?? 'text-gray-600';
    }

    /**
     * 取得點數顯示格式
     */
    public function getFormattedPointsAttribute()
    {
        $sign = $this->points >= 0 ? '+' : '';
        return $sign . $this->points;
    }
}

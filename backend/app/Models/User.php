<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
        'birthday',
        'gender',
        'points',
        'member_level',
        'avatar',
        'email_notifications',
        'line_user_id',
        'facebook_user_id',
        'google_user_id',
        'is_admin',
        'last_login_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
            'last_login_at' => 'datetime',
            'email_notifications' => 'boolean',
        ];
    }

    /**
     * 取得會員等級顯示名稱
     */
    public function getMemberLevelNameAttribute()
    {
        $levels = [
            'bronze' => '銅牌會員',
            'silver' => '銀牌會員',
            'gold' => '金牌會員',
            'platinum' => '白金會員',
        ];

        return $levels[$this->member_level] ?? '一般會員';
    }

    /**
     * 取得會員等級顏色
     */
    public function getMemberLevelColorAttribute()
    {
        $colors = [
            'bronze' => 'text-amber-600',
            'silver' => 'text-gray-500',
            'gold' => 'text-yellow-500',
            'platinum' => 'text-purple-500',
        ];

        return $colors[$this->member_level] ?? 'text-gray-600';
    }

    /**
     * 取得頭像URL
     */
    public function getAvatarUrlAttribute()
    {
        if ($this->avatar) {
            return asset('storage/' . $this->avatar);
        }

        // 預設頭像
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&color=7C3AED&background=F3E8FF';
    }

    /**
     * 檢查是否為高級會員
     */
    public function isPremiumMember()
    {
        return in_array($this->member_level, ['gold', 'platinum']);
    }

    /**
     * 增加點數
     */
    public function addPoints($points, $description = '')
    {
        $this->increment('points', $points);
        
        // 記錄點數異動
        PointTransaction::create([
            'user_id' => $this->id,
            'points' => $points,
            'type' => 'earn',
            'description' => $description,
        ]);

        // 檢查是否升級
        $this->checkLevelUpgrade();
    }

    /**
     * 使用點數
     */
    public function usePoints($points, $description = '')
    {
        if ($this->points < $points) {
            throw new \Exception('點數不足');
        }

        $this->decrement('points', $points);
        
        // 記錄點數異動
        PointTransaction::create([
            'user_id' => $this->id,
            'points' => -$points,
            'type' => 'spend',
            'description' => $description,
        ]);
    }

    /**
     * 檢查會員等級升級
     */
    public function checkLevelUpgrade()
    {
        // 以累積消費金額為依據（只計算已送達訂單）
        $totalSpent = $this->orders()->where('status', 'delivered')->sum('total');
        $newLevel = 'bronze';
        if ($totalSpent >= 50000) {
            $newLevel = 'platinum';
        } elseif ($totalSpent >= 20000) {
            $newLevel = 'gold';
        } elseif ($totalSpent >= 5000) {
            $newLevel = 'silver';
        }
        if ($newLevel !== $this->member_level) {
            $this->update(['member_level' => $newLevel]);
        }
    }

    /**
     * 關聯訂單
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * 關聯點數交易記錄
     */
    public function pointTransactions()
    {
        return $this->hasMany(PointTransaction::class);
    }

    /**
     * 關聯購物車
     */
    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
}

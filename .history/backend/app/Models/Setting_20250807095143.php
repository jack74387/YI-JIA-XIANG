<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'description',
        'type'
    ];

    /**
     * 獲取設定值
     */
    public static function get($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * 設定值
     */
    public static function set($key, $value, $description = null, $type = 'string')
    {
        return static::updateOrCreate(
            ['key' => $key],
            [
                'value' => $value,
                'description' => $description,
                'type' => $type
            ]
        );
    }

    /**
     * 獲取首頁背景圖
     */
    public static function getHeroBgImage()
    {
        return static::get('hero_bg_image', null);
    }

    /**
     * 設定首頁背景圖
     */
    public static function setHeroBgImage($imagePath)
    {
        return static::set(
            'hero_bg_image', 
            $imagePath, 
            '首頁主要背景圖片',
            'image'
        );
    }
}

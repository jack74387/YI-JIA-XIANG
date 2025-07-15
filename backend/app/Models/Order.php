<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'final_amount',
        'discount',
        'shipping_address',
        'shipping_method',
        'payment_method',
        'note',
        'recipient_name',
        'recipient_phone',
        'recipient_email'
    ];

    protected $casts = [
        'total' => 'integer',
        'final_amount' => 'integer',
        'discount' => 'integer',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
} 
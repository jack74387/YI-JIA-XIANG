<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSpecPrice extends Model
{
    protected $fillable = [
        'product_spec_id', 'price', 'label'
    ];

    public function spec()
    {
        return $this->belongsTo(ProductSpec::class, 'product_spec_id');
    }
}

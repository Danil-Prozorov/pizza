<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class OrderProducts extends Model
{
    protected $table = 'orderProducts';
    protected $guarded = [];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}

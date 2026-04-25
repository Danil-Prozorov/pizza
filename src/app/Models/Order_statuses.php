<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Order_statuses extends Model
{
    protected $guarded = [];

    public function order():BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}

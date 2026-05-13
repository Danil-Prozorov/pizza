<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\ProductStatuses;

class Product extends Model
{
    protected $guarded = [];

    public function product_status(): HasOne
    {
        return $this->hasOne(ProductStatuses::class, 'id', 'status');
    }
}

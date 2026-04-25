<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Product_statuses;

class Product extends Model
{
    protected $guarded = [];

    public function product_status(): HasOne
    {
        return $this->hasOne(Product_statuses::class, 'id', 'status');
    }
}

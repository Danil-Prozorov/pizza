<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Cart extends Model
{
    protected $guarded = [];

    public function products():HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}

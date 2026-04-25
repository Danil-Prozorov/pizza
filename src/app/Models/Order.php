<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;


class Order extends Model
{
    protected $guarded = [];

    public function ordered_products():HasMany
    {
        return $this->hasMany(Order_products::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}

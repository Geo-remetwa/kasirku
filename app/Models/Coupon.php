<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Models\ProductCategory;

class Coupon extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'coupon_code', 'name', 'description',
        'product_category_id', 'expired',
        'status', 'discount'
    ];

    protected $hidden = [];
}

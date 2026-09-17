<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\Product;

class Sale extends Model
{
    protected $fillable = [
        'transaction_code', 'product_id',
        'product_price', 'quantity', 'total_price'
    ];

    protected $hidden = [];

    // Relasi
    public function product(){
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}

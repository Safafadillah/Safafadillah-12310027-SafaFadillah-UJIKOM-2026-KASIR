<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PembelianDetail extends Model
{
    protected $fillable = [
        'pembelian_id',
        'product_id',
        'qty',
        'price',
        'subtotal'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
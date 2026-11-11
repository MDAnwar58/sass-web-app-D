<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Model;

class SaleItem extends Model
{
    protected $table = 'inv_sale_items';

    protected $fillable = [
        'sale_id', 'product_id', 'quantity',
        'unit_price', 'discount', 'subtotal',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

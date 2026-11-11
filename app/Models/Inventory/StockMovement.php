<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $table = 'inv_stock_movements';

    protected $fillable = [
        'organization_id', 'product_id', 'type', 'quantity',
        'stock_before', 'stock_after', 'reference_type',
        'reference_id', 'note',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}

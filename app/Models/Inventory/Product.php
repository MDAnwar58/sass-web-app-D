<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $table = 'inv_products';

    protected $fillable = [
        'organization_id', 'category_id', 'name', 'sku', 'barcode',
        'description', 'unit', 'purchase_price', 'selling_price',
        'stock_quantity', 'alert_quantity', 'image', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class, 'product_id');
    }
}

<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    protected $table = 'inv_purchases';

    protected $fillable = [
        'organization_id', 'supplier_id', 'reference', 'purchase_date',
        'subtotal', 'discount', 'tax', 'total', 'paid',
        'payment_status', 'note',
    ];

    protected $casts = ['purchase_date' => 'date'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PurchaseItem::class, 'purchase_id');
    }
}

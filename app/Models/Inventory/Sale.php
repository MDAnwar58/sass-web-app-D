<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use SoftDeletes;

    protected $table = 'inv_sales';

    protected $fillable = [
        'organization_id', 'customer_id', 'invoice_number', 'sale_date',
        'subtotal', 'discount', 'tax', 'total', 'paid',
        'payment_status', 'payment_method', 'note',
    ];

    protected $casts = ['sale_date' => 'date'];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(SaleItem::class, 'sale_id');
    }
}

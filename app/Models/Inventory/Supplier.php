<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    protected $table = 'inv_suppliers';

    protected $fillable = [
        'organization_id', 'name', 'email', 'phone',
        'company', 'address', 'payable',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}

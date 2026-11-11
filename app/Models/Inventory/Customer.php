<?php

namespace App\Models\Inventory;

use App\Models\Core\Organization;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'inv_customers';

    protected $fillable = [
        'organization_id', 'name', 'email', 'phone',
        'address', 'receivable',
    ];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'customer_id');
    }
}

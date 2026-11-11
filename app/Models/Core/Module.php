<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    protected $fillable = [
        'key', 'name', 'description',
        'monthly_price', 'yearly_price', 'is_active', 'features',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
    ];

    public function plans()
    {
        return $this->belongsToMany(Plan::class, 'plan_modules');
    }
}

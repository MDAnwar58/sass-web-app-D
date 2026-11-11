<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'monthly_price', 'yearly_price',
        'stripe_monthly_price_id', 'stripe_yearly_price_id',
        'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function modules()
    {
        return $this->belongsToMany(Module::class, 'plan_modules');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

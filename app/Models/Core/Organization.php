<?php

namespace App\Models\Core;

use App\Models\User;
use App\Modules\ModuleManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Billable;

class Organization extends Model
{
    use SoftDeletes, Billable;

    protected $fillable = [
        'name', 'slug', 'email', 'phone',
        'address', 'logo', 'timezone', 'currency', 'settings',
    ];

    protected $casts = ['settings' => 'array'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'organization_users')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class);
    }

    public function activeSubscription()
    {
        return $this->subscription()
            ->where('stripe_status', 'active')
            ->first();
    }

    public function hasActiveSubscription(): bool
    {
        return $this->activeSubscription() !== null;
    }

    public function moduleManager(): ModuleManager
    {
        return new ModuleManager($this);
    }

    public function getActiveModulesAttribute(): array
    {
        return $this->moduleManager()->activeModules();
    }
}

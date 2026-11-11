<?php

namespace App\Modules;

use App\Models\Core\Organization;
use Illuminate\Support\Facades\Cache;

class ModuleManager
{
    public function __construct(private Organization $organization) {}

    public function hasModule(string $moduleKey): bool
    {
        return Cache::remember(
            "org_{$this->organization->id}_module_{$moduleKey}",
            now()->addMinutes(30),
            fn () => $this->organization
                ->activeSubscription()
                ?->plan
                ->modules()
                ->where('key', $moduleKey)
                ->exists() ?? false
        );
    }

    public function activeModules(): array
    {
        return Cache::remember(
            "org_{$this->organization->id}_active_modules",
            now()->addMinutes(30),
            fn () => $this->organization
                ->activeSubscription()
                ?->plan
                ->modules()
                ->pluck('key')
                ->toArray() ?? []
        );
    }

    public function clearCache(): void
    {
        Cache::forget("org_{$this->organization->id}_active_modules");
    }
}

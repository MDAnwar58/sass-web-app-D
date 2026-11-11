<?php

namespace App\Http\Middleware;

use App\Modules\ModuleManager;
use Closure;
use Illuminate\Http\Request;

class CheckModuleAccess
{
    public function handle(Request $request, Closure $next, string $module): mixed
    {
        $organization = $request->user()->currentOrganization;

        if (! $organization) {
            return redirect()->route('organizations.create');
        }

        $manager = new ModuleManager($organization);

        if (! $manager->hasModule($module)) {
            return redirect()->route('subscription.upgrade')
                ->with('error', "The '{$module}' module is not included in your plan.");
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckOrganization
{
    public function handle(Request $request, Closure $next): mixed
    {
        if ($request->user() && ! $request->user()->currentOrganization) {
            return redirect()->route('organizations.create');
        }

        return $next($request);
    }
}

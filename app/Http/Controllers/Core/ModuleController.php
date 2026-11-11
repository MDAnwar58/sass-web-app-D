<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class ModuleController extends Controller
{
    public function index()
    {
        $org = auth()->user()->currentOrganization;
        $manager = $org?->moduleManager();

        return Inertia::render('Core/Modules/Index', [
            'activeModules' => $manager?->activeModules() ?? [],
        ]);
    }
}

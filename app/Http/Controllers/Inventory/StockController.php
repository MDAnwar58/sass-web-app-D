<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\StockMovement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StockController extends Controller
{
    public function report(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $movements = StockMovement::with('product')
            ->where('organization_id', $orgId)
            ->when($request->type, fn($q, $t) => $q->where('type', $t))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Reports/Stock', [
            'movements' => $movements,
            'filters' => $request->only(['type']),
        ]);
    }
}

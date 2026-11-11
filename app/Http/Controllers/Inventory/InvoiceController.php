<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Sale;

class InvoiceController extends Controller
{
    public function download(Sale $sale)
    {
        abort_unless(
            $sale->organization_id === auth()->user()->currentOrganization->id,
            403
        );

        $sale->load('items.product', 'customer');

        return inertia('Inventory/Sales/Invoice', [
            'sale' => $sale,
        ]);
    }
}

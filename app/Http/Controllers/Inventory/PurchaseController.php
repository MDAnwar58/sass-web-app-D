<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Purchase;
use App\Models\Inventory\Supplier;
use App\Models\Inventory\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class PurchaseController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $purchases = Purchase::with('supplier')
            ->where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('reference', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Purchases/Index', [
            'purchases' => $purchases,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $orgId = auth()->user()->currentOrganization->id;
        return Inertia::render('Inventory/Purchases/Create', [
            'suppliers' => Supplier::where('organization_id', $orgId)->get(),
            'products' => Product::where('organization_id', $orgId)
                ->where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'supplier_id' => 'required|exists:inv_suppliers,id',
            'purchase_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:inv_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $orgId = $request->user()->currentOrganization->id;
        $data['organization_id'] = $orgId;
        $data['reference'] = 'PUR-' . strtoupper(Str::random(8));

        $subtotal = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $data['subtotal'] = $subtotal;
        $data['total'] = $subtotal - ($data['discount'] ?? 0) + ($data['tax'] ?? 0);
        $data['payment_status'] = ($data['paid'] ?? 0) >= $data['total'] ? 'paid' : (($data['paid'] ?? 0) > 0 ? 'partial' : 'unpaid');

        $purchase = Purchase::create($data);

        foreach ($data['items'] as $item) {
            $purchase->items()->create($item);
            $product = Product::find($item['product_id']);
            $product->increment('stock_quantity', $item['quantity']);
        }

        return redirect()->route('inventory.purchases.index')
            ->with('success', 'Purchase created.');
    }

    public function destroy(Purchase $purchase)
    {
        $this->authorizeOrg($purchase);
        foreach ($purchase->items as $item) {
            $item->product->decrement('stock_quantity', $item->quantity);
        }
        $purchase->delete();
        return back()->with('success', 'Purchase deleted.');
    }

    private function authorizeOrg(Purchase $purchase): void
    {
        abort_unless(
            $purchase->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

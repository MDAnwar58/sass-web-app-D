<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Sale;
use App\Models\Inventory\Customer;
use App\Models\Inventory\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class SaleController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $sales = Sale::with('customer')
            ->where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('invoice_number', 'like', "%{$s}%"))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Sales/Index', [
            'sales' => $sales,
            'filters' => $request->only(['search']),
        ]);
    }

    public function create()
    {
        $orgId = auth()->user()->currentOrganization->id;
        return Inertia::render('Inventory/Sales/Create', [
            'customers' => Customer::where('organization_id', $orgId)->get(),
            'products' => Product::where('organization_id', $orgId)
                ->where('is_active', true)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_id' => 'nullable|exists:inv_customers,id',
            'sale_date' => 'required|date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:inv_products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.discount' => 'nullable|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'paid' => 'nullable|numeric|min:0',
            'payment_method' => 'required|in:cash,card,bkash,bank',
            'note' => 'nullable|string',
        ]);

        $orgId = $request->user()->currentOrganization->id;
        $data['organization_id'] = $orgId;
        $data['invoice_number'] = 'INV-' . strtoupper(Str::random(10));

        $subtotal = collect($data['items'])->sum(fn($i) => $i['quantity'] * $i['unit_price']);
        $data['subtotal'] = $subtotal;
        $data['total'] = $subtotal - ($data['discount'] ?? 0) + ($data['tax'] ?? 0);
        $data['payment_status'] = ($data['paid'] ?? 0) >= $data['total'] ? 'paid' : (($data['paid'] ?? 0) > 0 ? 'partial' : 'unpaid');

        $sale = Sale::create($data);

        foreach ($data['items'] as $item) {
            $sale->items()->create($item);
            $product = Product::find($item['product_id']);
            $product->decrement('stock_quantity', $item['quantity']);
        }

        return redirect()->route('inventory.sales.index')
            ->with('success', 'Sale created.');
    }

    public function destroy(Sale $sale)
    {
        $this->authorizeOrg($sale);
        foreach ($sale->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }
        $sale->delete();
        return back()->with('success', 'Sale deleted.');
    }

    private function authorizeOrg(Sale $sale): void
    {
        abort_unless(
            $sale->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

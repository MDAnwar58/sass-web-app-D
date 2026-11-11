<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Product;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $products = Product::with('category')
            ->where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('sku', 'like', "%{$s}%"))
            ->when($request->category_id, fn($q, $c) => $q->where('category_id', $c))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Products/Index', [
            'products' => $products,
            'categories' => Category::where('organization_id', $orgId)->get(),
            'filters' => $request->only(['search', 'category_id']),
        ]);
    }

    public function create()
    {
        $orgId = auth()->user()->currentOrganization->id;
        return Inertia::render('Inventory/Products/Create', [
            'categories' => Category::where('organization_id', $orgId)->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'nullable|string|unique:inv_products,sku',
            'category_id' => 'nullable|exists:inv_categories,id',
            'unit' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'alert_quantity' => 'required|integer|min:0',
        ]);

        $data['organization_id'] = $request->user()->currentOrganization->id;
        Product::create($data);

        return redirect()->route('inventory.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $this->authorizeOrg($product);
        $orgId = auth()->user()->currentOrganization->id;
        return Inertia::render('Inventory/Products/Edit', [
            'product' => $product->load('category'),
            'categories' => Category::where('organization_id', $orgId)->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $this->authorizeOrg($product);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'nullable|exists:inv_categories,id',
            'unit' => 'required|string',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'alert_quantity' => 'required|integer|min:0',
            'is_active' => 'boolean',
        ]);
        $product->update($data);
        return redirect()->route('inventory.products.index')
            ->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        $this->authorizeOrg($product);
        $product->delete();
        return back()->with('success', 'Product deleted.');
    }

    private function authorizeOrg(Product $product): void
    {
        abort_unless(
            $product->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

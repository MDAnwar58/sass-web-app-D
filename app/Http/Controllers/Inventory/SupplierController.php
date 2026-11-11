<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Supplier;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $suppliers = Supplier::where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('company', 'like', "%{$s}%"))
            ->withCount('purchases')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Suppliers/Index', [
            'suppliers' => $suppliers,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $data['organization_id'] = $request->user()->currentOrganization->id;
        Supplier::create($data);

        return back()->with('success', 'Supplier created.');
    }

    public function update(Request $request, Supplier $supplier)
    {
        $this->authorizeOrg($supplier);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'company' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $supplier->update($data);
        return back()->with('success', 'Supplier updated.');
    }

    public function destroy(Supplier $supplier)
    {
        $this->authorizeOrg($supplier);
        $supplier->delete();
        return back()->with('success', 'Supplier deleted.');
    }

    private function authorizeOrg(Supplier $supplier): void
    {
        abort_unless(
            $supplier->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Customer;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $customers = Customer::where('organization_id', $orgId)
            ->when($request->search, fn($q, $s) => $q->where('name', 'like', "%{$s}%")
                ->orWhere('email', 'like', "%{$s}%"))
            ->withCount('sales')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search']),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);

        $data['organization_id'] = $request->user()->currentOrganization->id;
        Customer::create($data);

        return back()->with('success', 'Customer created.');
    }

    public function update(Request $request, Customer $customer)
    {
        $this->authorizeOrg($customer);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
        ]);
        $customer->update($data);
        return back()->with('success', 'Customer updated.');
    }

    public function destroy(Customer $customer)
    {
        $this->authorizeOrg($customer);
        $customer->delete();
        return back()->with('success', 'Customer deleted.');
    }

    private function authorizeOrg(Customer $customer): void
    {
        abort_unless(
            $customer->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

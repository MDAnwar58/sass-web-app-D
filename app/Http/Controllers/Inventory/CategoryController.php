<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Models\Inventory\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $orgId = $request->user()->currentOrganization->id;

        $categories = Category::with('children')
            ->where('organization_id', $orgId)
            ->whereNull('parent_id')
            ->withCount('products')
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Inventory/Categories/Index', [
            'categories' => $categories,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:inv_categories,id',
        ]);

        $data['organization_id'] = $request->user()->currentOrganization->id;
        Category::create($data);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, Category $category)
    {
        $this->authorizeOrg($category);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);
        $category->update($data);
        return back()->with('success', 'Category updated.');
    }

    public function destroy(Category $category)
    {
        $this->authorizeOrg($category);
        $category->delete();
        return back()->with('success', 'Category deleted.');
    }

    private function authorizeOrg(Category $category): void
    {
        abort_unless(
            $category->organization_id === auth()->user()->currentOrganization->id,
            403
        );
    }
}

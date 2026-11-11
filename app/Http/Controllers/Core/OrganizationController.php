<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Core\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrganizationController extends Controller
{
    public function index()
    {
        return Inertia::render('Core/Organization/Index', [
            'organizations' => auth()->user()->organizations,
        ]);
    }

    public function create()
    {
        return Inertia::render('Core/Organization/Create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:organizations,slug',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
        ]);

        $org = Organization::create($data);

        $org->users()->attach(auth()->id(), [
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        session(['current_organization_id' => $org->id]);

        return redirect()->route('dashboard')->with('success', 'Organization created.');
    }

    public function switch(Request $request, Organization $org)
    {
        $user = $request->user();
        abort_unless($user->organizations()->where('organization_id', $org->id)->exists(), 403);

        session(['current_organization_id' => $org->id]);

        return redirect()->route('dashboard')->with('success', "Switched to {$org->name}.");
    }

    public function edit()
    {
        return Inertia::render('Core/Organization/Edit', [
            'organization' => auth()->user()->currentOrganization,
        ]);
    }

    public function update(Request $request)
    {
        $org = $request->user()->currentOrganization;
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'timezone' => 'required|string',
            'currency' => 'required|string|size:3',
        ]);
        $org->update($data);
        return back()->with('success', 'Organization updated.');
    }

    public function destroy()
    {
        $org = auth()->user()->currentOrganization;
        $org->delete();
        session()->forget('current_organization_id');
        return redirect()->route('organizations.create')->with('success', 'Organization deleted.');
    }
}

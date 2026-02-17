<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\LeadSourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeadSourceCategoryController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $categories = LeadSourceCategory::where('tenant_id', $tenantId)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->withCount('sources')
            ->get();

        return view('settings.lead-sources.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('settings.lead-sources.categories.create');
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:32'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slugBase = $validated['slug'] ?? $validated['name'];
        $slug = $this->makeUniqueSlug($tenantId, $slugBase);

        LeadSourceCategory::create([
            'tenant_id' => $tenantId,
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('settings.lead-source-categories.index')
            ->with('success', 'Lead source category created.');
    }

    public function edit(LeadSourceCategory $lead_source_category)
    {
        $this->abortIfNotTenantOwned($lead_source_category);

        return view('settings.lead-sources.categories.edit', [
            'category' => $lead_source_category,
        ]);
    }

    public function update(Request $request, LeadSourceCategory $lead_source_category)
    {
        $this->abortIfNotTenantOwned($lead_source_category);
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lead_source_categories', 'slug')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId))
                    ->ignore($lead_source_category->id),
            ],
            'description' => ['nullable', 'string'],
            'color' => ['nullable', 'string', 'max:32'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        // If slug is empty, regenerate from name (still ensure uniqueness)
        $slugBase = ($validated['slug'] ?? '') !== '' ? $validated['slug'] : $validated['name'];
        $slug = $this->makeUniqueSlug($tenantId, $slugBase, $lead_source_category->id);

        $lead_source_category->update([
            'name' => $validated['name'],
            'slug' => $slug,
            'description' => $validated['description'] ?? null,
            'color' => $validated['color'] ?? '#3B82F6',
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('settings.lead-source-categories.index')
            ->with('success', 'Lead source category updated.');
    }

    public function destroy(LeadSourceCategory $lead_source_category)
    {
        $this->abortIfNotTenantOwned($lead_source_category);
        $lead_source_category->delete();

        return redirect()->route('settings.lead-source-categories.index')
            ->with('success', 'Lead source category deleted.');
    }

    private function abortIfNotTenantOwned(LeadSourceCategory $category): void
    {
        if ($category->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }

    private function makeUniqueSlug(int $tenantId, string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        if ($slug === '') {
            $slug = 'category';
        }

        $query = LeadSourceCategory::where('tenant_id', $tenantId)->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        if (!$query->exists()) {
            return $slug;
        }

        $i = 2;
        while (true) {
            $try = "{$slug}-{$i}";
            $query = LeadSourceCategory::where('tenant_id', $tenantId)->where('slug', $try);
            if ($ignoreId) {
                $query->where('id', '!=', $ignoreId);
            }
            if (!$query->exists()) {
                return $try;
            }
            $i++;
        }
    }
}


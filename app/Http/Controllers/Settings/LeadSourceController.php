<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\LeadSource;
use App\Models\LeadSourceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class LeadSourceController extends Controller
{
    public function index()
    {
        $tenantId = Auth::user()->tenant_id;

        $sources = LeadSource::where('tenant_id', $tenantId)
            ->with('category')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('settings.lead-sources.sources.index', compact('sources'));
    }

    public function create()
    {
        $tenantId = Auth::user()->tenant_id;
        $categories = LeadSourceCategory::where('tenant_id', $tenantId)->orderBy('sort_order')->orderBy('name')->get();

        return view('settings.lead-sources.sources.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255'],
            'lead_source_category_id' => ['nullable', 'integer', 'exists:lead_source_categories,id'],
            'type' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slugBase = $validated['slug'] ?? $validated['name'];
        $slug = $this->makeUniqueSlug($tenantId, $slugBase);

        LeadSource::create([
            'tenant_id' => $tenantId,
            'lead_source_category_id' => $validated['lead_source_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? true),
        ]);

        return redirect()->route('settings.lead-sources.index')
            ->with('success', 'Lead source created.');
    }

    public function edit(LeadSource $lead_source)
    {
        $this->abortIfNotTenantOwned($lead_source);

        $tenantId = Auth::user()->tenant_id;
        $categories = LeadSourceCategory::where('tenant_id', $tenantId)->orderBy('sort_order')->orderBy('name')->get();

        return view('settings.lead-sources.sources.edit', [
            'source' => $lead_source,
            'categories' => $categories,
        ]);
    }

    public function update(Request $request, LeadSource $lead_source)
    {
        $this->abortIfNotTenantOwned($lead_source);
        $tenantId = Auth::user()->tenant_id;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('lead_sources', 'slug')
                    ->where(fn ($q) => $q->where('tenant_id', $tenantId))
                    ->ignore($lead_source->id),
            ],
            'lead_source_category_id' => ['nullable', 'integer', 'exists:lead_source_categories,id'],
            'type' => ['required', 'string', 'max:50'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $slugBase = ($validated['slug'] ?? '') !== '' ? $validated['slug'] : $validated['name'];
        $slug = $this->makeUniqueSlug($tenantId, $slugBase, $lead_source->id);

        $lead_source->update([
            'lead_source_category_id' => $validated['lead_source_category_id'] ?? null,
            'name' => $validated['name'],
            'slug' => $slug,
            'type' => $validated['type'],
            'sort_order' => $validated['sort_order'] ?? 0,
            'is_active' => (bool) ($validated['is_active'] ?? false),
        ]);

        return redirect()->route('settings.lead-sources.index')
            ->with('success', 'Lead source updated.');
    }

    public function destroy(LeadSource $lead_source)
    {
        $this->abortIfNotTenantOwned($lead_source);
        $lead_source->delete();

        return redirect()->route('settings.lead-sources.index')
            ->with('success', 'Lead source deleted.');
    }

    private function abortIfNotTenantOwned(LeadSource $source): void
    {
        if ($source->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }
    }

    private function makeUniqueSlug(int $tenantId, string $base, ?int $ignoreId = null): string
    {
        $slug = Str::slug($base);
        if ($slug === '') {
            $slug = 'source';
        }

        $query = LeadSource::where('tenant_id', $tenantId)->where('slug', $slug);
        if ($ignoreId) {
            $query->where('id', '!=', $ignoreId);
        }
        if (!$query->exists()) {
            return $slug;
        }

        $i = 2;
        while (true) {
            $try = "{$slug}-{$i}";
            $query = LeadSource::where('tenant_id', $tenantId)->where('slug', $try);
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


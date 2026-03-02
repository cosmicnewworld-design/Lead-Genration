<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Http\Request;

class PublicLeadCaptureController extends Controller
{
    public function show($tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();
        return view('public.capture', ['tenant_slug' => $tenant->slug]);
    }

    public function store(Request $request, $tenant_slug)
    {
        $tenant = Tenant::where('slug', $tenant_slug)->firstOrFail();

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
        ]);

        $lead = new Lead($validatedData);
        $lead->tenant_id = $tenant->id;
        $lead->save();

        return back()->with('success', 'Thank you! Your information has been submitted successfully.');
    }
}

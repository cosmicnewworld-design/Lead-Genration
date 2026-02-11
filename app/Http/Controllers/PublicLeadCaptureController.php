<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class PublicLeadCaptureController extends Controller
{
    /**
     * Display the public lead capture form.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();
        return view('public.capture', compact('tenant'));
    }

    /**
     * Store a newly created lead from the public form.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $slug)
    {
        $tenant = Tenant::where('slug', $slug)->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('leads')->where(function ($query) use ($tenant) {
                    return $query->where('tenant_id', $tenant->id);
                }),
            ],
            'phone' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $lead = new Lead();
        $lead->fill($validator->validated());
        $lead->tenant_id = $tenant->id;
        $lead->status = 'new';
        $lead->save();

        return redirect()->back()->with('success', 'Thank you for your submission!');
    }
}

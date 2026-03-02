<?php

namespace App\Http\Controllers;

use App\Models\AutomationRule;
use Illuminate\Http\Request;

class AutomationRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $automationRules = AutomationRule::all();
        return view('automation-rules.index', compact('automationRules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('automation-rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'trigger_source' => 'required|string',
            'trigger_event' => 'required|string',
            'action_type' => 'required|string',
            'action_data' => 'nullable|json',
        ]);

        $automationRule = new AutomationRule($validatedData);
        $automationRule->tenant_id = auth()->user()->tenant_id; // Assuming tenant_id is on the user model
        $automationRule->save();

        return redirect()->route('automation-rules.index')->with('success', 'Automation rule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(AutomationRule $automationRule)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(AutomationRule $automationRule)
    {
        return view('automation-rules.edit', compact('automationRule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AutomationRule $automationRule)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'trigger_source' => 'required|string',
            'trigger_event' => 'required|string',
            'action_type' => 'required|string',
            'action_data' => 'nullable|json',
        ]);

        $automationRule->update($validatedData);

        return redirect()->route('automation-rules.index')->with('success', 'Automation rule updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AutomationRule $automationRule)
    {
        $automationRule->delete();

        return redirect()->route('automation-rules.index')->with('success', 'Automation rule deleted successfully.');
    }
}

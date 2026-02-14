<?php

namespace App\Http\Controllers;

use App\Models\ScoringRule;
use Illuminate\Http\Request;

class ScoringRuleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rules = ScoringRule::where('tenant_id', session('tenant_id'))->get();
        return view('scoring-rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('scoring-rules.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
            'points' => 'required|integer',
        ]);

        ScoringRule::create($request->all() + ['tenant_id' => session('tenant_id')]);

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ScoringRule $scoringRule)
    {
        return view('scoring-rules.show', compact('scoringRule'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ScoringRule $scoringRule)
    {
        return view('scoring-rules.edit', compact('scoringRule'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ScoringRule $scoringRule)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
            'points' => 'required|integer',
        ]);

        $scoringRule->update($request->all());

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ScoringRule $scoringRule)
    {
        $scoringRule->delete();

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule deleted successfully');
    }
}

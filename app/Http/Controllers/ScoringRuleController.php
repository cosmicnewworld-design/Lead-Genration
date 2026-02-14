<?php

namespace App\Http\Controllers;

use App\Models\ScoringRule;
use App\Services\ScoringRuleService;
use Illuminate\Http\Request;

class ScoringRuleController extends Controller
{
    protected $scoringRuleService;

    public function __construct(ScoringRuleService $scoringRuleService)
    {
        $this->scoringRuleService = $scoringRuleService;
    }

    public function index()
    {
        $rules = $this->scoringRuleService->getRules();
        return view('scoring-rules.index', compact('rules'));
    }

    public function create()
    {
        return view('scoring-rules.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
            'points' => 'required|integer',
        ]);

        $this->scoringRuleService->createRule($request->all());

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule created successfully.');
    }

    public function show(ScoringRule $scoringRule)
    {
        return view('scoring-rules.show', compact('scoringRule'));
    }

    public function edit(ScoringRule $scoringRule)
    {
        return view('scoring-rules.edit', compact('scoringRule'));
    }

    public function update(Request $request, ScoringRule $scoringRule)
    {
        $request->validate([
            'field' => 'required|string',
            'value' => 'required|string',
            'points' => 'required|integer',
        ]);

        $this->scoringRuleService->updateRule($scoringRule, $request->all());

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule updated successfully');
    }

    public function destroy(ScoringRule $scoringRule)
    {
        $this->scoringRuleService->deleteRule($scoringRule);

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule deleted successfully');
    }
}

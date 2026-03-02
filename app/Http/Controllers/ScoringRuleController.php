<?php

namespace App\Http\Controllers;

use App\Models\ScoringRule;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScoringRuleController extends Controller
{
    /**
     * A list of available operators for the scoring rules.
     *
     * @var array
     */
    private $operators = [
        'equals' => 'Equals',
        'not_equals' => 'Not Equals',
        'contains' => 'Contains',
        'not_contains' => 'Does Not Contain',
        'is_filled' => 'Is Filled',
        'is_not_filled' => 'Is Not Filled',
        'greater_than' => 'Greater Than',
        'less_than' => 'Less Than',
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // The BelongsToTenant trait will automatically scope this query
        $rules = ScoringRule::latest()->get();
        return view('scoring_rules.index', ['rules' => $rules]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('scoring_rules.create', [
            'rule' => new ScoringRule(),
            'operators' => $this->operators
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'condition_field' => 'required|string|max:255',
            'operator' => ['required', Rule::in(array_keys($this->operators))],
            'condition_value' => 'nullable|string|max:255',
            'points' => 'required|integer',
        ]);

        // The BelongsToTenant trait will automatically add the tenant_id
        ScoringRule::create($validatedData);

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule created successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\View\View
     */
    public function edit(ScoringRule $scoringRule)
    {
        // Policy will ensure the user can only edit their own tenant's rules
        $this->authorize('update', $scoringRule);

        return view('scoring_rules.edit', [
            'rule' => $scoringRule,
            'operators' => $this->operators
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ScoringRule $scoringRule)
    {
        $this->authorize('update', $scoringRule);

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'condition_field' => 'required|string|max:255',
            'operator' => ['required', Rule::in(array_keys($this->operators))],
            'condition_value' => 'nullable|string|max:255',
            'points' => 'required|integer',
        ]);

        $scoringRule->update($validatedData);

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ScoringRule  $scoringRule
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ScoringRule $scoringRule)
    {
        $this->authorize('delete', $scoringRule);

        $scoringRule->delete();

        return redirect()->route('scoring-rules.index')
            ->with('success', 'Scoring rule deleted successfully');
    }
}

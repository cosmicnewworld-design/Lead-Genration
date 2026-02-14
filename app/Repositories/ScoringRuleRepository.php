<?php

namespace App\Repositories;

use App\Models\ScoringRule;
use Illuminate\Support\Facades\Auth;

class ScoringRuleRepository
{
    public function getRules()
    {
        return Auth::user()->tenant->scoringRules()->get();
    }

    public function create(array $data): ScoringRule
    {
        return Auth::user()->tenant->scoringRules()->create($data);
    }

    public function update(ScoringRule $rule, array $data): bool
    {
        return $rule->update($data);
    }

    public function delete(ScoringRule $rule): ?bool
    {
        return $rule->delete();
    }
}

<?php

namespace App\Services;

use App\Models\ScoringRule;
use App\Repositories\ScoringRuleRepository;

class ScoringRuleService
{
    protected $scoringRuleRepository;

    public function __construct(ScoringRuleRepository $scoringRuleRepository)
    {
        $this->scoringRuleRepository = $scoringRuleRepository;
    }

    public function getRules()
    {
        return $this->scoringRuleRepository->getRules();
    }

    public function createRule(array $data): ScoringRule
    {
        return $this->scoringRuleRepository->create($data);
    }

    public function updateRule(ScoringRule $rule, array $data): bool
    {
        return $this->scoringRuleRepository->update($rule, $data);
    }

    public function deleteRule(ScoringRule $rule): ?bool
    {
        return $this->scoringRuleRepository->delete($rule);
    }
}

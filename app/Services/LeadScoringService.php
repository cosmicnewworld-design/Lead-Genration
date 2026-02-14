<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\ScoringRule;

class LeadScoringService
{
    public function calculateScore(Lead $lead): array
    {
        $score = 0;
        $breakdown = [];
        $rules = ScoringRule::where('tenant_id', $lead->tenant_id)->get();

        foreach ($rules as $rule) {
            $field = $rule->field;
            $operator = 'contains'; // Hardcoded for now, will be dynamic later
            $value = $rule->value;
            $points = $rule->points;

            $leadValue = $lead->{$field};

            if ($this->evaluateRule($leadValue, $operator, $value)) {
                $score += $points;
                $breakdown[] = [
                    'field' => $field,
                    'value' => $value,
                    'points' => $points,
                    'description' => "Rule matched: Field '{$field}' contains '{$value}'. Points awarded: {$points}."
                ];
            }
        }

        return [
            'total_score' => $score,
            'breakdown' => $breakdown,
        ];
    }

    private function evaluateRule($leadValue, $operator, $value): bool
    {
        switch ($operator) {
            case 'equals':
                return $leadValue == $value;
            case 'contains':
                return str_contains(strtolower((string) $leadValue), strtolower((string) $value));
            // Add other operators as needed
            default:
                return false;
        }
    }

    public function updateLeadScore(Lead $lead): void
    {
        $scoringData = $this->calculateScore($lead);
        $lead->score = $scoringData['total_score'];
        $lead->save();
    }
}

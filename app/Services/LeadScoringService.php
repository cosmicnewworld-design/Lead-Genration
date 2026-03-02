<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\ScoringRule;
use Illuminate\Support\Facades\Log;

class LeadScoringService
{
    public function calculateScore(Lead $lead): int
    {
        $score = 0;
        // Scoring rules are now correctly scoped to the tenant via the model's global scope
        $rules = ScoringRule::all();

        foreach ($rules as $rule) {
            try {
                if ($this->isRuleConditionMet($lead, $rule)) {
                    $score += $rule->points;
                }
            } catch (\Exception $e) {
                Log::error("Error processing scoring rule #{$rule->id} for lead #{$lead->id}: " . $e->getMessage());
            }
        }

        return $score;
    }

    private function isRuleConditionMet(Lead $lead, ScoringRule $rule): bool
    {
        $fieldValue = $this->getLeadFieldValue($lead, $rule->condition_field);

        if (is_null($fieldValue) && $rule->operator !== 'is_not_filled') {
            return false;
        }

        switch ($rule->operator) {
            case 'equals':
                return strtolower($fieldValue) == strtolower($rule->condition_value);
            case 'not_equals':
                return strtolower($fieldValue) != strtolower($rule->condition_value);
            case 'contains':
                return str_contains(strtolower((string)$fieldValue), strtolower($rule->condition_value));
            case 'not_contains':
                return !str_contains(strtolower((string)$fieldValue), strtolower($rule->condition_value));
            case 'is_filled':
                return !empty($fieldValue);
            case 'is_not_filled':
                return empty($fieldValue);
            case 'greater_than':
                return is_numeric($fieldValue) && is_numeric($rule->condition_value) && $fieldValue > $rule->condition_value;
            case 'less_than':
                return is_numeric($fieldValue) && is_numeric($rule->condition_value) && $fieldValue < $rule->condition_value;
            default:
                return false;
        }
    }

    private function getLeadFieldValue(Lead $lead, string $field)
    {
        // This can be expanded to support related models or JSON fields
        return $lead->{$field};
    }
}

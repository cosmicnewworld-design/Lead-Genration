<?php

namespace App\Jobs;

use App\Models\Lead;
use App\Models\ScoringRule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CalculateLeadScore implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lead;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Lead $lead
     * @return void
     */
    public function __construct(Lead $lead)
    {
        $this->lead = $lead;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Load all scoring rules and the lead's activities
        $rules = ScoringRule::all();
        $activities = $this->lead->activities;

        if ($rules->isEmpty()) {
            $this->lead->score = 0;
            $this->lead->save();
            return;
        }

        $totalScore = 0;

        foreach ($rules as $rule) {
            $ruleMatched = false;

            if ($rule->condition_field === 'activity_type') {
                foreach ($activities as $activity) {
                    if ($this->evaluateCondition($activity->activity_type, $rule->operator, $rule->condition_value)) {
                        $totalScore += $rule->points;
                        $ruleMatched = true;
                        break; // Stop checking activities for this rule once one matches
                    }
                }
            } else {
                $leadValue = data_get($this->lead, $rule->condition_field);
                if ($this->evaluateCondition($leadValue, $rule->operator, $rule->condition_value)) {
                    $totalScore += $rule->points;
                    $ruleMatched = true;
                }
            }
        }

        $this->lead->score = $totalScore;
        $this->lead->save();
    }

    /**
     * Evaluate a condition based on the operator.
     *
     * @param mixed $actualValue
     * @param string $operator
     * @param mixed $ruleValue
     * @return bool
     */
    private function evaluateCondition($actualValue, $operator, $ruleValue): bool
    {
        switch ($operator) {
            case 'equals':
                return strtolower((string)$actualValue) == strtolower((string)$ruleValue);
            case 'not_equals':
                return strtolower((string)$actualValue) != strtolower((string)$ruleValue);
            case 'contains':
                return str_contains(strtolower((string)$actualValue), strtolower((string)$ruleValue));
            case 'greater_than':
                return is_numeric($actualValue) && is_numeric($ruleValue) && (float)$actualValue > (float)$ruleValue;
            case 'less_than':
                return is_numeric($actualValue) && is_numeric($ruleValue) && (float)$actualValue < (float)$ruleValue;
            default:
                return false;
        }
    }
}

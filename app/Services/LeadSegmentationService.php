<?php

namespace App\Services;

use App\Models\Lead;

class LeadSegmentationService
{
    public function segmentLead(Lead $lead)
    {
        if ($lead->score > 100) {
            $lead->segment = 'Hot';
        } elseif ($lead->score > 50) {
            $lead->segment = 'Warm';
        } else {
            $lead->segment = 'Cold';
        }

        $lead->save();
    }
}

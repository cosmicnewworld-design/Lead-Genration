<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadActivity;
use App\Jobs\CalculateLeadScore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LeadActivityController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'lead_id' => 'required|exists:leads,id',
            'activity_type' => 'required|string|max:255',
            'metadata' => 'nullable|json',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $validatedData = $validator->validated();

        // The metadata field is expected to be a JSON string from the request.
        // We'll decode it before passing it to the create method,
        // as the LeadActivity model will re-encode it to array automatically.
        if (isset($validatedData['metadata'])) {
            $validatedData['metadata'] = json_decode($validatedData['metadata'], true);
        }

        $activity = LeadActivity::create($validatedData);

        // After recording the activity, dispatch the job to recalculate the lead's score.
        $lead = Lead::find($validatedData['lead_id']);
        if ($lead) {
            CalculateLeadScore::dispatch($lead);
        }

        return response()->json([
            'success' => true,
            'message' => 'Activity tracked successfully',
            'data' => $activity
        ], 201);
    }
}

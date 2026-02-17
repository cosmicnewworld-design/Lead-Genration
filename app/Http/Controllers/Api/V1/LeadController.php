<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Display a listing of leads
     */
    public function index(Request $request): JsonResponse
    {
        $query = Lead::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        // Filter by score
        if ($request->has('min_score')) {
            $query->where('score', '>=', $request->min_score);
        }

        // Filter by assigned user
        if ($request->has('assigned_to')) {
            $query->where('assigned_to_user_id', $request->assigned_to);
        }

        // Search
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        $leads = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $leads,
        ]);
    }

    /**
     * Store a newly created lead
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'source' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $lead = Lead::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $lead,
            'message' => 'Lead created successfully',
        ], 201);
    }

    /**
     * Display the specified lead
     */
    public function show(Lead $lead): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $lead->load(['pipelineStage', 'assignedTo', 'tags']),
        ]);
    }

    /**
     * Update the specified lead
     */
    public function update(Request $request, Lead $lead): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'nullable|string|max:20',
            'company' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'sometimes|string|max:255',
            'score' => 'sometimes|integer',
            'is_qualified' => 'sometimes|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $lead->update($request->all());

        return response()->json([
            'success' => true,
            'data' => $lead,
            'message' => 'Lead updated successfully',
        ]);
    }

    /**
     * Remove the specified lead
     */
    public function destroy(Lead $lead): JsonResponse
    {
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully',
        ]);
    }
}

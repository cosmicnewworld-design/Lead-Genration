<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $request->validate([
            'activity_type' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $lead->activities()->create([
            'user_id' => auth()->id(),
            'activity_type' => $request->activity_type,
            'description' => $request->description,
        ]);

        return back()->with('success', 'Activity added successfully.');
    }
}

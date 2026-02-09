<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\OutreachEmail;
use App\Http\Controllers\AiController;

class BusinessController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $businesses = Business::latest()->paginate(5);
        return view('businesses.index', compact('businesses'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('businesses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'whatsapp_number' => 'required',
            'contact_email' => 'required|email',
            'target_audience' => 'required',
        ]);

        Business::create($request->all());

        return redirect()->route('businesses.index')
            ->with('success', 'Business created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Business $business)
    {
        $leads = $business->leads()->orderBy('created_at', 'desc')->get();
        return view('businesses.show', compact('business', 'leads'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Business $business)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'whatsapp_number' => 'required',
            'contact_email' => 'required|email',
            'target_audience' => 'required',
        ]);

        $business->update($request->all());

        return redirect()->route('businesses.index')
            ->with('success', 'Business updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Business $business)
    {
        $business->delete();

        return redirect()->route('businesses.index')
            ->with('success', 'Business deleted successfully');
    }

    /**
     * Show the outreach page for the specified business.
     */
    public function outreach(Business $business)
    {
        return view('businesses.outreach', compact('business'));
    }

    /**
     * Send outreach messages to leads.
     */
    public function sendOutreach(Request $request, Business $business)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message_template' => 'required|string',
        ]);

        $leads = $business->leads;
        $aiController = app(AiController::class);
        $personalizedMessages = [];

        foreach ($leads as $lead) {
            $personalizedMessage = $aiController->personalizeMessage($request->message_template, $lead->scraped_data);
            $personalizedMessages[] = ['body' => $personalizedMessage];
            
            Mail::to($lead->email)->send(new OutreachEmail($request->subject, $personalizedMessage));
        }

        return redirect()->route('businesses.outreach', $business)
                         ->with('personalized_messages', $personalizedMessages)
                         ->with('success', 'Outreach messages sent successfully.');
    }
}

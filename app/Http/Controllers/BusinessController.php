<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\BusinessService;
use Illuminate\Http\Request;

class BusinessController extends Controller
{
    protected $businessService;

    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    public function index()
    {
        $businesses = $this->businessService->getAllBusinesses();
        return view('businesses.index', compact('businesses'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('businesses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'whatsapp_number' => 'required',
            'contact_email' => 'required|email',
            'target_audience' => 'required',
        ]);

        $this->businessService->createBusiness($request->all());

        return redirect()->route('businesses.index')
            ->with('success', 'Business created successfully.');
    }

    public function show($id)
    {
        $business = $this->businessService->getBusinessById($id);
        return view('businesses.show', compact('business'));
    }

    public function edit(Business $business)
    {
        return view('businesses.edit', compact('business'));
    }

    public function update(Request $request, Business $business)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'whatsapp_number' => 'required',
            'contact_email' => 'required|email',
            'target_audience' => 'required',
        ]);

        $this->businessService->updateBusiness($business, $request->all());

        return redirect()->route('businesses.index')
            ->with('success', 'Business updated successfully');
    }

    public function destroy(Business $business)
    {
        $this->businessService->deleteBusiness($business);

        return redirect()->route('businesses.index')
            ->with('success', 'Business deleted successfully');
    }

    public function outreach(Business $business)
    {
        return view('businesses.outreach', compact('business'));
    }

    public function sendOutreach(Request $request, Business $business)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message_template' => 'required|string',
        ]);

        $personalizedMessages = $this->businessService->sendOutreach($business, $request->subject, $request->message_template);

        return redirect()->route('businesses.outreach', $business)
                         ->with('personalized_messages', $personalizedMessages)
                         ->with('success', 'Outreach messages sent successfully.');
    }
}

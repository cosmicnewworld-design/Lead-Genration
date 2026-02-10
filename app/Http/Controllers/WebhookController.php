<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lead;

class WebhookController extends Controller
{
    public function handleWhatsApp(Request $request)
    {
        $payload = $request->all();

        // Extract relevant data from the webhook payload
        // This will vary depending on the WhatsApp provider
        $phoneNumber = $payload['entry'][0]['changes'][0]['value']['messages'][0]['from'];
        $status = $payload['entry'][0]['changes'][0]['value']['statuses'][0]['status'];

        // Find the lead by phone number and update the status
        $lead = Lead::where('phone', $phoneNumber)->first();
        if ($lead) {
            $lead->whatsapp_status = $status;
            $lead->save();
        }

        return response()->json(['status' => 'success']);
    }
}

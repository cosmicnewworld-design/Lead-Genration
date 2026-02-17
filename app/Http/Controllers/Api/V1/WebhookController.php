<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook from external services
     */
    public function handle(Request $request, Tenant $tenant): JsonResponse
    {
        $providedSecret = $request->header('X-Webhook-Secret') ?? $request->query('secret');
        if (!$tenant->webhook_secret || !hash_equals((string) $tenant->webhook_secret, (string) $providedSecret)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized webhook request',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'event' => 'required|string',
            'data' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        $event = $request->input('event');
        $data = $request->input('data');

        try {
            switch ($event) {
                case 'lead.created':
                    $this->handleLeadCreated($tenant, $data);
                    break;
                case 'lead.updated':
                    $this->handleLeadUpdated($tenant, $data);
                    break;
                case 'email.replied':
                    $this->handleEmailReplied($tenant, $data);
                    break;
                case 'email.opened':
                    $this->handleEmailOpened($tenant, $data);
                    break;
                case 'email.clicked':
                    $this->handleEmailClicked($tenant, $data);
                    break;
                default:
                    Log::warning('Unknown webhook event', [
                        'event' => $event,
                        'tenant_id' => $tenant->id,
                    ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Webhook processed successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Webhook processing error', [
                'error' => $e->getMessage(),
                'event' => $event,
                'tenant_id' => $tenant->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to process webhook',
            ], 500);
        }
    }

    /**
     * Handle lead.created event
     */
    private function handleLeadCreated(Tenant $tenant, array $data): void
    {
        $webhookLeadSourceId = \App\Models\LeadSource::where('tenant_id', $tenant->id)
            ->where('slug', 'webhook')
            ->value('id');

        Lead::create([
            'tenant_id' => $tenant->id,
            'name' => $data['name'] ?? '',
            'email' => $data['email'] ?? '',
            'phone' => $data['phone'] ?? null,
            'company' => $data['company'] ?? null,
            'source' => $data['source'] ?? 'webhook',
            'lead_source_id' => $webhookLeadSourceId,
        ]);
    }

    /**
     * Handle lead.updated event
     */
    private function handleLeadUpdated(Tenant $tenant, array $data): void
    {
        if (!isset($data['id']) && !isset($data['email'])) {
            throw new \Exception('Lead ID or email is required');
        }

        $lead = isset($data['id'])
            ? Lead::where('id', $data['id'])->where('tenant_id', $tenant->id)->first()
            : Lead::where('email', $data['email'])->where('tenant_id', $tenant->id)->first();

        if ($lead) {
            $lead->update($data);
        }
    }

    /**
     * Handle email.replied event
     */
    private function handleEmailReplied(Tenant $tenant, array $data): void
    {
        $lead = Lead::where('email', $data['email'])
            ->where('tenant_id', $tenant->id)
            ->first();

        if ($lead) {
            $lead->update([
                'last_contacted_at' => now(),
                'status' => 'replied',
            ]);
        }
    }

    /**
     * Handle email.opened event
     */
    private function handleEmailOpened(Tenant $tenant, array $data): void
    {
        // Update campaign lead pivot if campaign_id is provided
        if (isset($data['campaign_id']) && isset($data['lead_id'])) {
            $campaign = \App\Models\Campaign::find($data['campaign_id']);
            if ($campaign) {
                $campaign->leads()->updateExistingPivot($data['lead_id'], [
                    'opened_at' => now(),
                ]);
            }
        }
    }

    /**
     * Handle email.clicked event
     */
    private function handleEmailClicked(Tenant $tenant, array $data): void
    {
        // Update campaign lead pivot if campaign_id is provided
        if (isset($data['campaign_id']) && isset($data['lead_id'])) {
            $campaign = \App\Models\Campaign::find($data['campaign_id']);
            if ($campaign) {
                $campaign->leads()->updateExistingPivot($data['lead_id'], [
                    'clicked_at' => now(),
                ]);
            }
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\LeadAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LeadAttachmentController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $request->validate([
            'attachment' => ['required', 'file', 'max:10240'], // max 10MB
        ]);

        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $originalFilename = $file->getClientOriginalName();
            $storagePath = $file->store('attachments/' . $lead->id, 'public');

            $lead->attachments()->create([
                'user_id' => Auth::id(),
                'original_filename' => $originalFilename,
                'storage_path' => $storagePath,
                'file_size' => $file->getSize(),
            ]);

            return back()->with('success', 'File uploaded successfully.');
        }

        return back()->with('error', 'File upload failed.');
    }

    public function download(Lead $lead, LeadAttachment $attachment)
    {
        // Ensure the attachment belongs to the lead
        if ($attachment->lead_id !== $lead->id) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->storage_path, $attachment->original_filename);
    }

    public function destroy(Lead $lead, LeadAttachment $attachment)
    {
        // Ensure the attachment belongs to the lead
        if ($attachment->lead_id !== $lead->id) {
            abort(404);
        }

        // Delete the file from storage
        Storage::disk('public')->delete($attachment->storage_path);

        // Delete the record from the database
        $attachment->delete();

        return back()->with('success', 'File deleted successfully.');
    }
}

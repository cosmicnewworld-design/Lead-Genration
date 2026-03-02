<?php

namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Attachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    public function store(Request $request, Lead $lead)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // Max 10MB
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('attachments', $fileName, 'local');

            $lead->attachments()->create([
                'user_id' => auth()->id(),
                'file_name' => $fileName,
                'file_path' => $filePath,
            ]);

            return back()->with('success', 'Attachment uploaded successfully.');
        }

        return back()->with('error', 'File not uploaded.');
    }

    public function download(Lead $lead, Attachment $attachment)
    {
        // Ensure the attachment belongs to the lead
        if ($attachment->lead_id !== $lead->id) {
            abort(404);
        }

        return Storage::disk('local')->download($attachment->file_path, $attachment->file_name);
    }

    public function destroy(Lead $lead, Attachment $attachment)
    {
        // Ensure the attachment belongs to the lead
        if ($attachment->lead_id !== $lead->id) {
            abort(404);
        }

        // Delete the file from storage
        Storage::disk('local')->delete($attachment->file_path);

        // Delete the attachment record from the database
        $attachment->delete();

        return back()->with('success', 'Attachment deleted successfully.');
    }
}

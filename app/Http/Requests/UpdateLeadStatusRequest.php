<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLeadStatusRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // For now, we'll allow any authenticated user to update a lead status.
        // In a multi-user application, you would add logic here to ensure
        // the user owns this lead.
        // For example: return $this->user()->can('update', $this->route('lead'));
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'status' => 'required|string|in:New,Contacted,Replied,Junk',
        ];
    }
}

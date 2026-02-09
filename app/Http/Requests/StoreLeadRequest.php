<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // Anyone can create a lead for now.
        // You might want to add authorization logic later, e.g.:
        // return $this->user()->can('create', Lead::class);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $leadId = $this->route('lead') ? $this->route('lead')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:leads,email,' . $leadId,
            'phone' => 'nullable|string|max:50',
            'designation' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'linkedin_profile' => 'nullable|url|max:255',
            'status' => 'sometimes|string|in:New,Contacted,Replied,Junk',
            'business_id' => 'required|exists:businesses,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages(): array
    {
        return [
            'business_id.required' => 'A business must be selected to associate with this lead.',
            'business_id.exists' => 'The selected business does not exist in our records.',
            'email.unique' => 'A lead with this email address already exists.',
        ];
    }
}

<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // We'll check if the user can update the specific lead model.
        // This assumes you have a LeadPolicy set up. If not, returning true is a start.
        // return $this->user()->can('update', $this->route('lead'));
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $leadId = $this->route('lead')->id;

        return [
            'name' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('leads', 'email')->ignore($leadId),
            ],
            'title' => 'nullable|string|max:255',
            'company_name' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'phone' => 'nullable|string|max:50',
            'status' => 'sometimes|string|in:New,Contacted,Replied,Junk',
            'business_id' => 'sometimes|required|exists:businesses,id',
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
            'business_id.exists' => 'The selected business does not exist in our records.',
            'email.unique' => 'Another lead with this email address already exists.',
        ];
    }
}

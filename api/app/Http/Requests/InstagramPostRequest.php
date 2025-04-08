<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstagramPostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'imageOrder' => 'required|array',
            'chatCompletion' => 'required|string'
        ];
    }

    public function messages(): array
    {
        return [
            'imageOrder.required' => 'The image order field is required.',
            'chatCompletion.required' => 'The chat completion field is required.',
        ];
    }
}

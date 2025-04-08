<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SplitFileRequest extends FormRequest
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
            'carouselFiles' => 'required|array',
            'carouselFiles.*' => 'file|mimes:png|max:2048',
            'dirName' => 'required|string',
        ];
    }

    public function messages(): array
    {
        return [
            'carouselFiles.required' => 'The carousel files are required.',
            'carouselFiles.array' => 'The carousel files must be an array.',
            'carouselFiles.*.file' => 'Each file must be a valid file.',
            'carouselFiles.*.mimes' => 'Each file must be a PNG image.',
            'carouselFiles.*.max' => 'Each file may not be greater than 2MB.',
        ];
    }
}

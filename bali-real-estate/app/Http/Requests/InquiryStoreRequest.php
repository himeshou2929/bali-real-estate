<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InquiryStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'property_id' => 'required|exists:properties,id',
            'name' => 'required|max:100',
            'email' => 'required|email',
            'phone' => 'nullable|max:50',
            'message' => 'nullable|max:2000',
        ];
    }
}
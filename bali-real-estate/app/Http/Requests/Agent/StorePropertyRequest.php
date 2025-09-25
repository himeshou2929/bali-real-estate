<?php

namespace App\Http\Requests\Agent;

use Illuminate\Foundation\Http\FormRequest;

class StorePropertyRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }
    public function rules(): array {
        return [
            'title' => ['required','string','max:255'],
            'address' => ['nullable','string','max:500'],
            'price_usd' => ['required','numeric','min:0'],
            'currency' => ['required','in:USD,JPY,IDR'],
            'area_id' => ['nullable','integer','exists:areas,id'],
            'bedrooms' => ['nullable','integer','min:0','max:50'],
            'bathrooms' => ['nullable','integer','min:0','max:50'],
            'yield_percent' => ['nullable','numeric','min:0','max:100'],
            'description' => ['nullable','string','max:5000'],
            'status' => ['required','in:available,pending,unavailable'],
            'featured_image' => ['nullable','image','mimes:jpeg,png,webp','max:10240'],
            'is_published' => ['boolean'],
            'monthly_rent' => ['nullable','integer','min:0'],
            'yearly_rent' => ['nullable','integer','min:0'],
            'leasehold_price' => ['nullable','integer','min:0'],
            'freehold_price' => ['nullable','integer','min:0'],
        ];
    }
}
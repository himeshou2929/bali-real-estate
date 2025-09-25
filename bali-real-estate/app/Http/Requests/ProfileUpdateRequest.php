<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check(); }

    public function rules(): array
    {
        $userId = $this->user()->id;
        
        return [
            'name'     => ['required','string','max:255'],
            'email'    => ['required','email','max:255','unique:users,email,'.$userId],
            'phone'    => ['nullable','string','max:30'],
            'language' => ['required','in:ja,en,id'],
            'currency' => ['required','in:JPY,USD,IDR'],
            'avatar'   => ['nullable','image','mimes:jpeg,png,webp','max:5120'],
            'company'  => ['nullable','string','max:255'],
            'title'    => ['nullable','string','max:255'],
            'bio'      => ['nullable','string','max:1000'],
            'website'  => ['nullable','string','max:255'],
            'whatsapp' => ['nullable','string','max:30'],
            'line_id'  => ['nullable','string','max:50'],
            'location' => ['nullable','string','max:255'],
        ];
    }
}

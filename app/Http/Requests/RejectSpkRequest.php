<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RejectSpkRequest extends FormRequest
{
    public function authorize(): bool
    {
        $spk = $this->route('spk');

        return $spk !== null && ($this->user()?->can('reject', $spk) ?? false);
    }

    public function rules(): array
    {
        return [
            'alasan_batal' => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'alasan_batal.required' => 'Alasan penolakan / pembatalan SPK wajib diisi.',
            'alasan_batal.min'      => 'Alasan penolakan minimal 5 karakter.',
            'alasan_batal.max'      => 'Alasan penolakan maksimal 1000 karakter.',
        ];
    }
}

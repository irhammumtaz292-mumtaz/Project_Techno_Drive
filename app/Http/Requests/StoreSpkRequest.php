<?php

namespace App\Http\Requests;

use App\Models\Spk;
use Illuminate\Foundation\Http\FormRequest;

class StoreSpkRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Spk::class) ?? false;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'integer', 'exists:projects,id'],
            'tgl_spk'    => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'project_id.required' => 'Proyek wajib dipilih.',
            'project_id.exists'   => 'Proyek yang dipilih tidak ditemukan.',
            'tgl_spk.required'    => 'Tanggal SPK wajib diisi.',
            'tgl_spk.date'        => 'Format tanggal SPK tidak valid.',
        ];
    }
}

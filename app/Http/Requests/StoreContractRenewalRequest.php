<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractRenewalRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'contract_number' => ['nullable', 'string', 'max:100'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'position' => ['required', 'string', 'max:100'],
            'branch' => ['required', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Custom attribute names in Indonesian.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'contract_number' => 'nomor kontrak/PKWT',
            'start_date' => 'tanggal mulai perpanjangan',
            'end_date' => 'tanggal habis perpanjangan',
            'position' => 'jabatan/posisi',
            'branch' => 'cabang',
            'notes' => 'catatan perpanjangan',
        ];
    }

    /**
     * Custom error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'end_date.after' => 'Tanggal habis kontrak harus setelah tanggal mulai perpanjangan.',
        ];
    }
}

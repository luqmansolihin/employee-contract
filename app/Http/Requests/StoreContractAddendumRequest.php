<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreContractAddendumRequest extends FormRequest
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
            'addendum_number' => ['required', 'string', 'max:255', 'unique:contract_addendums,addendum_number'],
            'issue_date' => ['required', 'date'],
            'effective_date' => ['required', 'date'],
            'new_end_date' => ['required', 'date', 'after:effective_date'],
            'new_position' => ['nullable', 'string', 'max:255'],
            'new_salary' => ['nullable', 'numeric', 'min:0'],
            'amendment_reason' => ['required', 'string', 'max:500'],
            'clause_changes' => ['nullable', 'string'],
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'addendum_number' => 'nomor adendum kontrak',
            'issue_date' => 'tanggal penerbitan adendum',
            'effective_date' => 'tanggal efektif berlaku',
            'new_end_date' => 'tanggal berakhir baru',
            'new_position' => 'jabatan/posisi baru',
            'new_salary' => 'gaji baru',
            'amendment_reason' => 'alasan perubahan/perpanjangan',
            'clause_changes' => 'klausul/pasal perubahan',
        ];
    }
}

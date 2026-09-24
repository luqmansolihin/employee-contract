<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
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
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->filled('addendum_number') && $this->filled('kode')) {
            $kode = strtoupper(trim((string) $this->kode));
            $addendumNumber = trim((string) $this->addendum_number);
            if (! str_ends_with($addendumNumber, '/'.$kode)) {
                $addendumNumber = $addendumNumber.'/'.$kode;
            }
            $this->merge([
                'addendum_number' => $addendumNumber,
                'kode' => $kode,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'addendum_number' => ['required', 'string', 'max:255', 'unique:contract_addendums,addendum_number'],
            'kode' => ['required', 'string', 'max:50'],
            'issue_date' => ['required', 'date'],
            'bidang' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'effective_date' => ['required', 'date'],
            'new_end_date' => ['required', 'date', 'after:effective_date'],
            'new_position' => ['nullable', 'string', 'max:255'],
            'new_salary' => ['nullable', 'numeric', 'min:0'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_position' => ['required', 'string', 'max:255'],
            'office_address' => ['required', 'string'],
            'amendment_reason' => ['nullable', 'string', 'max:500'],
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
            'kode' => 'kode surat',
            'issue_date' => 'tanggal surat',
            'bidang' => 'bidang',
            'branch' => 'cabang/penempatan',
            'effective_date' => 'tanggal efektif berlaku',
            'new_end_date' => 'tanggal berakhir baru',
            'new_position' => 'jabatan/posisi baru',
            'new_salary' => 'gaji baru',
            'supervisor_name' => 'nama atasan',
            'supervisor_position' => 'jabatan atasan',
            'office_address' => 'alamat kantor',
            'amendment_reason' => 'alasan perubahan/perpanjangan',
            'clause_changes' => 'klausul/pasal perubahan',
        ];
    }
}

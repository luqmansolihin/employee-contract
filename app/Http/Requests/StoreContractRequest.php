<?php

namespace App\Http\Requests;

use App\Models\OfferingLetter;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreContractRequest extends FormRequest
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
        if ($this->filled('contract_number') && $this->filled('kode')) {
            $kode = strtoupper(trim((string) $this->kode));
            $contractNumber = trim((string) $this->contract_number);
            if (! str_ends_with($contractNumber, '/' . $kode)) {
                $contractNumber = $contractNumber . '/' . $kode;
            }
            $this->merge([
                'contract_number' => $contractNumber,
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
            'employee_id' => ['required', 'exists:employees,id'],
            'offering_letter_id' => [
                'nullable',
                'exists:offering_letters,id',
                function ($attribute, $value, $fail) {
                    if ($value) {
                        $ol = OfferingLetter::find($value);
                        if ($ol && $ol->status !== 'accepted') {
                            $fail('Kontrak kerja hanya dapat diterbitkan untuk Surat Penawaran yang berstatus Diterima (Accepted).');
                        }
                    }
                },
            ],
            'contract_number' => ['required', 'string', 'max:255', 'unique:employee_contracts,contract_number'],
            'kode' => ['required', 'string', 'max:50'],
            'contract_date' => ['required', 'date'],
            'contract_type' => ['required', 'in:PKWT,MT,MAGANG'],
            'position' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_position' => ['required', 'string', 'max:255'],
            'office_address' => ['required', 'string'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string'],
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
            'employee_id' => 'karyawan',
            'offering_letter_id' => 'surat penawaran kerja',
            'contract_number' => 'nomor kontrak kerja',
            'kode' => 'kode surat',
            'contract_date' => 'tanggal surat',
            'contract_type' => 'tipe kontrak',
            'position' => 'jabatan/posisi',
            'bidang' => 'bidang',
            'branch' => 'cabang/penempatan',
            'start_date' => 'tanggal mulai kontrak',
            'end_date' => 'tanggal berakhir kontrak',
            'supervisor_name' => 'nama atasan',
            'supervisor_position' => 'jabatan atasan',
            'office_address' => 'alamat kantor',
            'basic_salary' => 'gaji pokok/uang saku',
            'allowance' => 'tunjangan',
            'notes' => 'catatan kontrak',
        ];
    }
}

<?php

namespace App\Http\Requests;

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
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'employee_id' => ['required', 'exists:employees,id'],
            'offering_letter_id' => ['nullable', 'exists:offering_letters,id'],
            'contract_number' => ['required', 'string', 'max:255', 'unique:employee_contracts,contract_number'],
            'contract_type' => ['required', 'in:PKWT,MT,MAGANG'],
            'position' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
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
            'contract_type' => 'tipe kontrak',
            'position' => 'jabatan/posisi',
            'branch' => 'cabang/penempatan',
            'start_date' => 'tanggal mulai kontrak',
            'end_date' => 'tanggal berakhir kontrak',
            'basic_salary' => 'gaji pokok/uang saku',
            'allowance' => 'tunjangan',
            'notes' => 'catatan kontrak',
        ];
    }
}

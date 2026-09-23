<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferingLetterRequest extends FormRequest
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
        $offeringLetterId = $this->route('offering_letter')?->id;

        return [
            'letter_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('offering_letters', 'letter_number')->ignore($offeringLetterId),
            ],
            'kode' => ['required', 'string', 'max:50'],
            'offer_date' => ['required', 'date'],
            'contract_type' => ['nullable', 'in:PKWT,MT,MAGANG'],
            'position' => ['required', 'string', 'max:255'],
            'bidang' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'proposed_start_date' => ['required', 'date'],
            'proposed_end_date' => ['required', 'date', 'after:proposed_start_date'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date'],
            'status' => ['nullable', 'in:draft,sent,accepted,rejected'],
            'terms' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'supervisor_name' => ['required', 'string', 'max:255'],
            'supervisor_position' => ['required', 'string', 'max:255'],
            'office_address' => ['required', 'string'],
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
            'letter_number' => 'nomor surat penawaran',
            'kode' => 'kode surat',
            'offer_date' => 'tanggal surat',
            'contract_type' => 'tipe kontrak',
            'position' => 'jabatan/posisi',
            'bidang' => 'bidang',
            'branch' => 'cabang/penempatan',
            'proposed_start_date' => 'tanggal awal kontrak',
            'proposed_end_date' => 'tanggal akhir kontrak',
            'basic_salary' => 'gaji pokok/uang saku',
            'allowance' => 'tunjangan',
            'valid_until' => 'berlaku hingga',
            'status' => 'status penawaran',
            'terms' => 'syarat & ketentuan',
            'notes' => 'catatan tambahan',
            'supervisor_name' => 'nama atasan',
            'supervisor_position' => 'jabatan atasan',
            'office_address' => 'alamat kantor',
        ];
    }
}

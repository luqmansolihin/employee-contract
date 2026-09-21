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
            'offer_date' => ['required', 'date'],
            'contract_type' => ['required', 'in:PKWT,MT,MAGANG'],
            'position' => ['required', 'string', 'max:255'],
            'branch' => ['required', 'string', 'max:255'],
            'proposed_start_date' => ['required', 'date'],
            'proposed_end_date' => ['required', 'date', 'after:proposed_start_date'],
            'basic_salary' => ['required', 'numeric', 'min:0'],
            'allowance' => ['nullable', 'numeric', 'min:0'],
            'valid_until' => ['nullable', 'date', 'after_or_equal:offer_date'],
            'status' => ['required', 'in:draft,sent,accepted,rejected'],
            'terms' => ['nullable', 'string'],
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
            'letter_number' => 'nomor surat penawaran',
            'offer_date' => 'tanggal penawaran',
            'contract_type' => 'tipe kontrak',
            'position' => 'jabatan/posisi',
            'branch' => 'cabang/penempatan',
            'proposed_start_date' => 'tanggal mulai kerja',
            'proposed_end_date' => 'tanggal selesai kerja',
            'basic_salary' => 'gaji pokok/uang saku',
            'allowance' => 'tunjangan',
            'valid_until' => 'berlaku hingga',
            'status' => 'status penawaran',
            'terms' => 'syarat & ketentuan',
            'notes' => 'catatan tambahan',
        ];
    }
}

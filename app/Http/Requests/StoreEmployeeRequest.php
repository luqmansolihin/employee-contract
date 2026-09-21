<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmployeeRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'ktp_number' => ['required', 'string', 'digits:16', Rule::unique('employees', 'ktp_number')],
            'gender' => ['required', Rule::in(['Laki-laki', 'Perempuan'])],
            'birth_place' => ['required', 'string', 'max:100'],
            'birth_date' => ['required', 'date', 'before:today'],
            'address' => ['required', 'string', 'max:1000'],
            'position' => ['required', 'string', 'max:100'],
            'branch' => ['required', 'string', 'max:100'],
            'join_date' => ['required', 'date'],
            'contract_end_date' => ['required', 'date', 'after:join_date'],
            'contract_number' => ['nullable', 'string', 'max:100'],
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
            'name' => 'nama karyawan',
            'ktp_number' => 'nomor KTP',
            'gender' => 'jenis kelamin',
            'birth_place' => 'tempat lahir',
            'birth_date' => 'tanggal lahir',
            'address' => 'alamat',
            'position' => 'jabatan',
            'branch' => 'cabang',
            'join_date' => 'tanggal mulai bergabung',
            'contract_end_date' => 'tanggal habis kontrak',
            'contract_number' => 'nomor kontrak',
            'notes' => 'catatan',
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
            'ktp_number.digits' => 'Nomor KTP harus tepat 16 digit angka.',
            'ktp_number.unique' => 'Nomor KTP ini sudah terdaftar dalam sistem.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'contract_end_date.after' => 'Tanggal habis kontrak harus setelah tanggal bergabung.',
        ];
    }
}

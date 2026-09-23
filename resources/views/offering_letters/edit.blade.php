@extends('layouts.app')

@section('title', 'Edit Offering Letter - ' . $offeringLetter->letter_number)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
            <span>/</span>
            <a href="{{ route('offering-letters.index') }}" class="hover:text-[#3C50E0] transition">Offering Letter</a>
            <span>/</span>
            <a href="{{ route('offering-letters.show', $offeringLetter) }}"
                class="hover:text-[#3C50E0] transition">{{ $offeringLetter->letter_number }}</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Ubah Data</span>
        </div>

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <h2 class="text-xl font-bold text-[#1C2434] mb-1">Ubah Surat Penawaran Kerja</h2>
            <p class="text-xs text-slate-500 mb-6">Perbarui data penawaran untuk kandidat
                {{ $offeringLetter->employee->name }}.</p>

            <form action="{{ route('offering-letters.update', $offeringLetter) }}" method="POST" class="space-y-6"
                autocomplete="off">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="letter_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Nomor Surat Penawaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="letter_number" id="letter_number"
                            value="{{ old('letter_number', $offeringLetter->letter_number) }}" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition font-mono">
                        @error('letter_number')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="kode" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Kode <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="kode" id="kode" value="{{ old('kode', $offeringLetter->kode) }}"
                            required autocomplete="off" placeholder="Contoh: HRD, CKU, OPS"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition font-mono uppercase">
                        @error('kode')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="offer_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Tanggal Penawaran <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="offer_date" id="offer_date"
                            value="{{ old('offer_date', $offeringLetter->offer_date->format('Y-m-d')) }}" required
                            autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('offer_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="bidang" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Bidang <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="bidang" id="bidang"
                            value="{{ old('bidang', $offeringLetter->bidang) }}" required autocomplete="off"
                            placeholder="Contoh: Operasional, IT, Keuangan"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('bidang')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="contract_type" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Rencana Tipe Kontrak <span class="text-rose-500">*</span>
                        </label>
                        <select name="contract_type" id="contract_type" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                            <option value="PKWT"
                                {{ old('contract_type', $offeringLetter->contract_type) == 'PKWT' ? 'selected' : '' }}>PKWT
                            </option>
                            <option value="MT"
                                {{ old('contract_type', $offeringLetter->contract_type) == 'MT' ? 'selected' : '' }}>MT
                            </option>
                            <option value="MAGANG"
                                {{ old('contract_type', $offeringLetter->contract_type) == 'MAGANG' ? 'selected' : '' }}>
                                MAGANG</option>
                        </select>
                        @error('contract_type')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Status Penawaran <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                            <option value="draft"
                                {{ old('status', $offeringLetter->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="sent"
                                {{ old('status', $offeringLetter->status) == 'sent' ? 'selected' : '' }}>Terkirim</option>
                            <option value="accepted"
                                {{ old('status', $offeringLetter->status) == 'accepted' ? 'selected' : '' }}>Diterima
                                (Accepted)</option>
                            <option value="rejected"
                                {{ old('status', $offeringLetter->status) == 'rejected' ? 'selected' : '' }}>Ditolak
                            </option>
                        </select>
                        @error('status')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Posisi / Jabatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="position" id="position"
                            value="{{ old('position', $offeringLetter->position) }}" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('position')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Cabang / Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="branch" id="branch"
                            value="{{ old('branch', $offeringLetter->branch) }}" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('branch')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proposed_start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Tanggal Mulai <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="proposed_start_date" id="proposed_start_date"
                            value="{{ old('proposed_start_date', $offeringLetter->proposed_start_date->format('Y-m-d')) }}"
                            required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('proposed_start_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="proposed_end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Tanggal Akhir Kontrak <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="proposed_end_date" id="proposed_end_date"
                            value="{{ old('proposed_end_date', $offeringLetter->proposed_end_date->format('Y-m-d')) }}"
                            required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('proposed_end_date')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="basic_salary" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Gaji Pokok / Saku (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="basic_salary" id="basic_salary"
                            value="{{ old('basic_salary', (float) $offeringLetter->basic_salary) }}" required
                            autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition font-mono">
                        @error('basic_salary')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="allowance" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Tunjangan (Rp) <span class="text-rose-500">*</span>
                        </label>
                        <input type="number" name="allowance" id="allowance"
                            value="{{ old('allowance', (float) $offeringLetter->allowance) }}" required
                            autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition font-mono">
                        @error('allowance')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="valid_until" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Berlaku Hingga (Batas Konfirmasi) <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="valid_until" id="valid_until"
                            value="{{ old('valid_until', $offeringLetter->valid_until ? $offeringLetter->valid_until->format('Y-m-d') : date('Y-m-d', strtotime('+7 days'))) }}"
                            required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('valid_until')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="terms" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Syarat & Ketentuan <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="terms" id="terms" rows="3" required
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">{{ old('terms', $offeringLetter->terms ?: 'Mengikuti peraturan dan tata tertib perusahaan yang berlaku.') }}</textarea>
                        @error('terms')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="notes" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Catatan Internal <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="notes" id="notes"
                            value="{{ old('notes', $offeringLetter->notes ?: '-') }}" required autocomplete="off"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border border-[#E2E8F0] focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('notes')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supervisor_name" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Nama Atasan yang TTD <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="supervisor_name" id="supervisor_name"
                            value="{{ old('supervisor_name', $offeringLetter->supervisor_name ?: 'Hendra Wijaya, S.Psi.') }}"
                            required autocomplete="off" placeholder="Contoh: Hendra Wijaya, S.Psi."
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_name') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('supervisor_name')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="supervisor_position" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Jabatan Atasan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="supervisor_position" id="supervisor_position"
                            value="{{ old('supervisor_position', $offeringLetter->supervisor_position ?: 'Human Resources Manager') }}"
                            required autocomplete="off" placeholder="Contoh: Human Resources Manager"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('supervisor_position')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <label for="office_address" class="block text-xs font-bold text-[#1C2434] mb-1">
                            Alamat Kantor <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="office_address" id="office_address"
                            value="{{ old('office_address', $offeringLetter->office_address ?: 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat') }}"
                            required autocomplete="off"
                            placeholder="Contoh: Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat"
                            class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('office_address') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] outline-hidden transition">
                        @error('office_address')
                            <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ route('offering-letters.show', $offeringLetter) }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">Batal</a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition">Simpan
                        Perubahan</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const letterNumberInput = document.getElementById('letter_number');
            const kodeInput = document.getElementById('kode');

            function syncLetterNumberWithKode() {
                if (!letterNumberInput) return;
                const rawKode = kodeInput ? kodeInput.value.trim().toUpperCase() : '';

                let currentNumber = letterNumberInput.value.trim();
                const olPos = currentNumber.indexOf('/OL');
                let base = olPos !== -1 ? currentNumber.substring(0, olPos + 3) : currentNumber;

                if (rawKode) {
                    letterNumberInput.value = base + '/' + rawKode;
                } else {
                    letterNumberInput.value = base;
                }
            }

            if (kodeInput) {
                kodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                    syncLetterNumberWithKode();
                });
            }
        });
    </script>
@endsection

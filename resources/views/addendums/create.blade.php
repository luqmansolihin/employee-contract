@extends('layouts.app')

@section('title', 'Buat Adendum Kontrak - ' . ($contract->contract_number ?: '#' . $contract->id))

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Karyawan</a>
            <span>/</span>
            <a href="{{ route('employees.show', $contract->employee) }}"
                class="hover:text-[#3C50E0] transition">{{ $contract->employee->name }}</a>
            <span>/</span>
            <a href="{{ route('contracts.show', $contract) }}" class="hover:text-[#3C50E0] transition">Kontrak</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Buat Adendum</span>
        </div>

        <!-- Workflow Banner -->
        <div class="p-4 rounded-2xl bg-purple-50 border border-purple-200 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div
                    class="w-10 h-10 rounded-xl bg-purple-600 text-white flex items-center justify-center shrink-0 font-bold">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>
                </div>
                <div>
                    <span class="text-[10px] font-extrabold uppercase tracking-wider text-purple-700 block">Alur Tahap 4:
                        Adendum Kontrak</span>
                    <h3 class="text-sm font-bold text-purple-950">
                        Penerbitan Adendum {{ App\Services\LetterNumberService::romanMonth($nextSequence) }} untuk Kontrak
                        Induk #{{ $contract->contract_number ?: $contract->id }}
                    </h3>
                </div>
            </div>

            <div class="text-right text-xs">
                <span class="text-purple-700 block">Karyawan: <strong>{{ $contract->employee->name }}</strong></span>
                <span class="text-purple-600 text-[11px]">Masa Kontrak Saat Ini: s/d
                    {{ $contract->end_date->format('d/m/Y') }}</span>
            </div>
        </div>

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form action="{{ route('addendums.store', $contract) }}" method="POST" class="space-y-6">
                @csrf

                <!-- Section 1: Informasi Adendum -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Data Penerbitan Adendum
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Adendum -->
                        <div>
                            <label for="addendum_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Surat Adendum <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="addendum_number" id="addendum_number"
                                value="{{ old('addendum_number', $suggestedNumber) }}" required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('addendum_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: Nomor/Bulan
                                Romawi/Tahun/A-{{ $contract->contract_type }}</p>
                            @error('addendum_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Terbit Adendum -->
                        <div>
                            <label for="issue_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Penerbitan Adendum <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="issue_date" id="issue_date"
                                value="{{ old('issue_date', date('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('issue_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('issue_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 2: Perpanjangan Masa Berlaku -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Perpanjangan Masa Berlaku & Periode Baru
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tanggal Efektif Berlaku -->
                        <div>
                            <label for="effective_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Efektif Berlaku Adendum <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="effective_date" id="effective_date"
                                value="{{ old('effective_date', $defaultEffectiveDate->format('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('effective_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Masa berakhir kontrak sebelumnya:
                                {{ $contract->end_date->format('d/m/Y') }}</p>
                            @error('effective_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Berakhir Baru -->
                        <div>
                            <label for="new_end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Berakhir Baru (Perpanjangan) <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="new_end_date" id="new_end_date"
                                value="{{ old('new_end_date', $defaultNewEndDate->format('Y-m-d')) }}" required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('new_end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('new_end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Perubahan Jabatan & Remunerasi (Opsional) -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        3. Penyesuaian Jabatan & Gaji (Bila Ada)
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Posisi / Jabatan Baru -->
                        <div>
                            <label for="new_position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Jabatan Baru (Bila Terjadi Promosi / Mutasi)
                            </label>
                            <input type="text" name="new_position" id="new_position"
                                value="{{ old('new_position', $contract->position) }}"
                                placeholder="Biarkan sama jika tidak ada perubahan"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('new_position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Jabatan sebelumnya:
                                <strong>{{ $contract->position }}</strong></p>
                            @error('new_position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji Baru -->
                        <div>
                            <label for="new_salary" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Gaji Pokok Baru (Rp)
                            </label>
                            <input type="number" name="new_salary" id="new_salary" step="1000" min="0"
                                value="{{ old('new_salary', $contract->basic_salary ? (float) $contract->basic_salary : 0) }}"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('new_salary') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Gaji sebelumnya:
                                <strong>{{ $contract->formatted_salary ?: 'Belum diatur' }}</strong></p>
                            @error('new_salary')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 4: Alasan & Klausul Perubahan -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        4. Alasan & Klausul Perubahan
                    </h4>

                    <div class="space-y-4">
                        <div>
                            <label for="amendment_reason" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Pokok Perubahan / Alasan Adendum <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="amendment_reason" id="amendment_reason"
                                value="{{ old('amendment_reason', 'Perpanjangan Masa Berlaku Perjanjian Kerja & Penyesuaian Remunerasi') }}"
                                required placeholder="Contoh: Perpanjangan Masa Berlaku Kontrak Kerja 1 Tahun"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('amendment_reason') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('amendment_reason')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="clause_changes" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Rincian Klausul / Pasal Perubahan (Opsional)
                            </label>
                            <textarea name="clause_changes" id="clause_changes" rows="3"
                                placeholder="Klausul spesifik yang diubah, misal: Mengubah Pasal 1 ayat (1) mengenai Jangka Waktu Perjanjian..."
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('clause_changes') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">{{ old('clause_changes', "1. Mengubah Pasal 1 mengenai Jangka Waktu Perjanjian Kerja menjadi terhitung sejak tanggal efektif hingga tanggal berakhir baru.\n2. Seluruh ketentuan lain dalam Perjanjian Kerja Induk yang tidak diubah dalam Adendum ini dinyatakan tetap berlaku dan mengikat.") }}</textarea>
                            @error('clause_changes')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ route('contracts.show', $contract) }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Terbitkan & Perpanjang Kontrak</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

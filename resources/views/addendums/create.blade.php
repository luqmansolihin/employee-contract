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
            <form action="{{ route('addendums.store', $contract) }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf

                <!-- Section 1: Informasi Adendum -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Data Penerbitan Adendum
                    </h4>

                    @php
                        $rawAddendumNumber = old('addendum_number', $suggestedNumber);
                        $currentKode = old('kode', $contract->kode);
                        $cleanKode = strtoupper(trim((string) $currentKode));
                        if ($cleanKode !== '' && str_ends_with($rawAddendumNumber, '/' . $cleanKode)) {
                            $displayAddendumNumber = substr($rawAddendumNumber, 0, -strlen('/' . $cleanKode));
                        } else {
                            $displayAddendumNumber = $rawAddendumNumber;
                        }
                    @endphp

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Adendum -->
                        <div>
                            <label for="addendum_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Surat Adendum <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="addendum_number" id="addendum_number"
                                value="{{ $displayAddendumNumber }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('addendum_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[11px] text-slate-500 mt-1 font-mono">
                                Nomor Adendum Lengkap: <span id="full_number_preview"
                                    class="font-bold text-[#3C50E0]">{{ $rawAddendumNumber }}</span>
                            </p>
                            @error('addendum_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Kode <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode" value="{{ old('kode', $contract->kode) }}"
                                required autocomplete="off" placeholder="Contoh: HRD, CKU, OPS"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('kode') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono uppercase outline-hidden transition">
                            @error('kode')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label for="issue_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Surat <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="issue_date" id="issue_date"
                                value="{{ old('issue_date', date('Y-m-d')) }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('issue_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('issue_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="bidang" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Bidang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="bidang" id="bidang"
                                value="{{ old('bidang', $contract->bidang) }}" required autocomplete="off"
                                placeholder="Contoh: Operasional, IT, Keuangan"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('bidang') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('bidang')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang / Unit Kerja -->
                        <div class="sm:col-span-2">
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Penempatan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $contract->branch) }}" required autocomplete="off"
                                placeholder="Contoh: Kantor Pusat Jakarta"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
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
                                autocomplete="off"
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
                                autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('new_end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('new_end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section 3: Penandatangan & Alamat Kantor -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        3. Penandatangan & Alamat Kantor
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nama Atasan -->
                        <div>
                            <label for="supervisor_name" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nama Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_name" id="supervisor_name"
                                value="{{ old('supervisor_name', $contract->supervisor_name) }}" required
                                autocomplete="off" placeholder="Contoh: Hendra Wijaya, S.Psi."
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_name') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('supervisor_name')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jabatan Atasan -->
                        <div>
                            <label for="supervisor_position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Jabatan Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_position" id="supervisor_position"
                                value="{{ old('supervisor_position', $contract->supervisor_position) }}" required
                                autocomplete="off" placeholder="Contoh: Human Resources Manager"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('supervisor_position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('supervisor_position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Alamat Kantor -->
                        <div class="sm:col-span-2">
                            <label for="office_address" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Alamat Kantor <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="office_address" id="office_address"
                                value="{{ old('office_address', $contract->office_address) }}" required
                                autocomplete="off"
                                placeholder="Contoh: Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('office_address') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('office_address')
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

    <!-- Script for Live Addendum Number Preview with /KODE -->
    <script>
        function updateFullNumberPreview() {
            const addendumNumberInput = document.getElementById('addendum_number');
            const kodeInput = document.getElementById('kode');
            const previewEl = document.getElementById('full_number_preview');
            if (!addendumNumberInput || !previewEl) return;
            const rawNumber = addendumNumberInput.value.trim();
            const rawKode = kodeInput ? kodeInput.value.trim().toUpperCase() : '';

            if (rawNumber && rawKode && !rawNumber.endsWith('/' + rawKode)) {
                previewEl.textContent = rawNumber + '/' + rawKode;
            } else {
                previewEl.textContent = rawNumber || '-';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const addendumNumberInput = document.getElementById('addendum_number');
            const kodeInput = document.getElementById('kode');

            if (kodeInput) {
                kodeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                    updateFullNumberPreview();
                });
            }

            if (addendumNumberInput) {
                addendumNumberInput.addEventListener('input', updateFullNumberPreview);
            }

            updateFullNumberPreview();
        });
    </script>
@endsection

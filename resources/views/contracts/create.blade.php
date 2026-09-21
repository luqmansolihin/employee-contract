@extends('layouts.app')

@section('title', 'Terbitkan Kontrak Kerja Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Dashboard</a>
            <span>/</span>
            <a href="{{ route('contracts.index') }}" class="hover:text-[#3C50E0] transition">Kontrak Kerja</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Terbitkan Kontrak</span>
        </div>

        @if ($offeringLetter)
            <!-- Offering Letter Origin Banner -->
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-emerald-600 text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-emerald-700 block">Alur Tahap
                            3: Kontrak Resmi dari Offering Letter</span>
                        <h3 class="text-sm font-bold text-emerald-950">
                            Merujuk ke Offering Letter #{{ $offeringLetter->letter_number }}
                        </h3>
                    </div>
                </div>

                <div class="text-right text-xs">
                    <span class="text-emerald-700 block">Karyawan: <strong>{{ $employee->name }}</strong></span>
                    <span class="text-emerald-600 text-[11px]">Gaji Disepakati:
                        {{ $offeringLetter->formatted_total_compensation }}</span>
                </div>
            </div>
        @else
            <!-- Standard Workflow Banner -->
            <div class="p-4 rounded-2xl bg-[#3C50E0]/10 border border-[#3C50E0]/20 flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#3C50E0] text-white flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <span class="text-[10px] font-extrabold uppercase tracking-wider text-[#3C50E0] block">Alur Tahap 3:
                            Penerbitan Kontrak Kerja</span>
                        <h3 class="text-sm font-bold text-[#1C2434]">Terbitkan Surat Perjanjian Kerja (PKWT, MT, MAGANG)
                        </h3>
                    </div>
                </div>
            </div>
        @endif

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form action="{{ route('contracts.store') }}" method="POST" class="space-y-6">
                @csrf

                @if ($offeringLetter)
                    <input type="hidden" name="offering_letter_id" value="{{ $offeringLetter->id }}">
                    <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                @endif

                <!-- Section: Pilih Karyawan & Tipe Kontrak -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Pihak Karyawan & Klasifikasi Kontrak
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @if (!$offeringLetter)
                            <!-- Dropdown Pilih Karyawan -->
                            <div>
                                <label for="employee_id" class="block text-xs font-bold text-[#1C2434] mb-1">
                                    Pilih Karyawan <span class="text-rose-500">*</span>
                                </label>
                                <select name="employee_id" id="employee_id" required
                                    class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('employee_id') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                    <option value="">-- Pilih Karyawan --</option>
                                    @foreach ($employees as $emp)
                                        <option value="{{ $emp->id }}" data-position="{{ $emp->current_position }}"
                                            data-branch="{{ $emp->current_branch }}"
                                            {{ old('employee_id', $employee?->id) == $emp->id ? 'selected' : '' }}>
                                            {{ $emp->name }} (NIK: {{ $emp->ktp_number }}) -
                                            {{ $emp->current_position }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('employee_id')
                                    <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @else
                            <div>
                                <label class="block text-xs font-bold text-[#1C2434] mb-1">Karyawan Terpilih</label>
                                <div
                                    class="px-3.5 py-2 text-xs rounded-xl bg-slate-50 border border-slate-200 text-slate-700 font-bold">
                                    {{ $employee->name }} (NIK: {{ $employee->ktp_number }})
                                </div>
                            </div>
                        @endif

                        <!-- Tipe Kontrak -->
                        <div>
                            <label for="contract_type" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tipe Kontrak Kerja <span class="text-rose-500">*</span>
                            </label>
                            <select name="contract_type" id="contract_type" required
                                onchange="updateContractNumberSuggestion()"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_type') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                <option value="PKWT"
                                    {{ old('contract_type', $selectedType) == 'PKWT' ? 'selected' : '' }}>PKWT (Perjanjian
                                    Kerja Waktu Tertentu)</option>
                                <option value="MT" {{ old('contract_type', $selectedType) == 'MT' ? 'selected' : '' }}>
                                    MT (Management Trainee)</option>
                                <option value="MAGANG"
                                    {{ old('contract_type', $selectedType) == 'MAGANG' ? 'selected' : '' }}>MAGANG
                                    (Internship / Pemagangan)</option>
                            </select>
                            @error('contract_type')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nomor Kontrak -->
                        <div class="sm:col-span-2">
                            <label for="contract_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Kontrak Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="contract_number" id="contract_number"
                                value="{{ old('contract_number', $suggestedNumber) }}" required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: Nomor/Bulan Romawi/Tahun/{PKWT|MT|MAGANG}.
                                Nomor reset ke 1 setiap tahun baru.</p>
                            @error('contract_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Jabatan, Lokasi & Periode -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Penempatan & Masa Berlaku Kontrak
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Jabatan -->
                        <div>
                            <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Jabatan / Posisi <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                value="{{ old('position', $offeringLetter ? $offeringLetter->position : $employee?->current_position) }}"
                                required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang -->
                        <div>
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $offeringLetter ? $offeringLetter->branch : $employee?->current_branch) }}"
                                required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai -->
                        <div>
                            <label for="start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Mulai Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="start_date" id="start_date"
                                value="{{ old('start_date', $offeringLetter ? $offeringLetter->proposed_start_date->format('Y-m-d') : ($employee?->first_join_date ? $employee->first_join_date->format('Y-m-d') : date('Y-m-d'))) }}"
                                required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('start_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('start_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai -->
                        <div>
                            <label for="end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Berakhir Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="end_date" id="end_date"
                                value="{{ old('end_date', $offeringLetter ? $offeringLetter->proposed_end_date->format('Y-m-d') : date('Y-m-d', strtotime('+1 year -1 day'))) }}"
                                required
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Gaji & Tunjangan -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        3. Remunerasi & Catatan
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Gaji Pokok -->
                        <div>
                            <label for="basic_salary" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Gaji Pokok / Uang Saku (Rp)
                            </label>
                            <input type="number" name="basic_salary" id="basic_salary" step="1000" min="0"
                                value="{{ old('basic_salary', $offeringLetter ? (float) $offeringLetter->basic_salary : 0) }}"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('basic_salary') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            @error('basic_salary')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan -->
                        <div>
                            <label for="allowance" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tunjangan Lainnya (Rp)
                            </label>
                            <input type="number" name="allowance" id="allowance" step="1000" min="0"
                                value="{{ old('allowance', $offeringLetter ? (float) $offeringLetter->allowance : 0) }}"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('allowance') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            @error('allowance')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan -->
                        <div class="sm:col-span-2">
                            <label for="notes" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Catatan / Klausul Tambahan
                            </label>
                            <textarea name="notes" id="notes" rows="2"
                                placeholder="Keterangan masa percobaan, tugas pokok, atau kesepakatan khusus"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('notes') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">{{ old('notes') }}</textarea>
                            @error('notes')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ route('contracts.index') }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Terbitkan & Simpan Kontrak</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script for Dynamic Number Suggestion based on type -->
    <script>
        const suggestedNumbers = @json($suggestedNumbers);

        function updateContractNumberSuggestion() {
            const type = document.getElementById('contract_type').value;
            if (suggestedNumbers[type]) {
                document.getElementById('contract_number').value = suggestedNumbers[type];
            }
        }

        @if (!$offeringLetter)
            document.getElementById('employee_id')?.addEventListener('change', function() {
                const selected = this.options[this.selectedIndex];
                if (selected) {
                    const pos = selected.getAttribute('data-position');
                    const branch = selected.getAttribute('data-branch');
                    if (pos) document.getElementById('position').value = pos;
                    if (branch) document.getElementById('branch').value = branch;
                }
            });
        @endif
    </script>
@endsection

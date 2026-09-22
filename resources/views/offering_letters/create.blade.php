@extends('layouts.app')

@section('title', 'Buat Offering Letter' . ($employee ? ' - ' . $employee->name : ''))

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb -->
        <div class="flex items-center gap-2 text-xs text-[#8A99AD]">
            <a href="{{ route('offering-letters.index') }}" class="hover:text-[#3C50E0] transition">Offering Letter</a>
            <span>/</span>
            <span class="text-slate-800 font-medium">Buat Offering Letter</span>
            @if ($employee)
                <span>/</span>
                <a href="{{ route('employees.show', $employee) }}"
                    class="hover:text-[#3C50E0] transition">{{ $employee->name }}</a>
            @endif
        </div>

        <!-- Banner -->
        <div class="p-4 rounded-2xl bg-[#3C50E0]/10 border border-[#3C50E0]/20 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-[#3C50E0] text-white flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-[#1C2434]">Terbitkan Surat Penawaran Kerja Resmi</h3>
                    <p class="text-xs text-slate-500">Pilih karyawan terdaftar dan lengkapi detail penawaran kerja.</p>
                </div>
            </div>

            @if ($employee)
                <div class="text-right text-xs">
                    <span class="text-slate-400 block">Karyawan Terpilih:</span>
                    <span class="font-bold text-[#1C2434]">{{ $employee->name }}</span>
                </div>
            @endif
        </div>

        <!-- Form Card -->
        <div class="p-6 sm:p-8 rounded-2xl bg-white border border-[#E2E8F0] shadow-xs">
            <form action="{{ route('offering-letters.store') }}" method="POST" class="space-y-6" autocomplete="off">
                @csrf

                <!-- Section: Pemilihan Karyawan -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Karyawan Penerima Penawaran
                    </h4>

                    @if ($employee)
                        <input type="hidden" name="employee_id" value="{{ $employee->id }}">
                        <div
                            class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between gap-4">
                            <div>
                                <span class="text-xs text-slate-400 block">Karyawan Terdaftar:</span>
                                <span class="text-sm font-bold text-[#1C2434]">{{ $employee->name }}</span>
                                <span class="text-xs text-slate-500 font-mono ml-2">(NIK:
                                    {{ $employee->ktp_number }})</span>
                            </div>
                            <a href="{{ route('offering-letters.create') }}"
                                class="text-xs text-[#3C50E0] hover:underline font-semibold">
                                Ganti Karyawan
                            </a>
                        </div>
                    @else
                        <div>
                            <label for="employee_id" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Pilih Karyawan Terdaftar <span class="text-rose-500">*</span>
                            </label>
                            <select name="employee_id" id="employee_id" required autocomplete="off"
                                onchange="fillEmployeeDetails(this)"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('employee_id') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                <option value="">-- Pilih Karyawan Terdaftar --</option>
                                @foreach ($employees as $emp)
                                    <option value="{{ $emp->id }}" data-position="{{ $emp->current_position }}"
                                        data-branch="{{ $emp->current_branch }}"
                                        data-start-date="{{ $emp->first_join_date ? $emp->first_join_date->format('Y-m-d') : '' }}"
                                        {{ old('employee_id', request('employee_id')) == $emp->id ? 'selected' : '' }}>
                                        {{ $emp->name }} (NIK:
                                        {{ $emp->ktp_number }}){{ $emp->current_position ? ' - ' . $emp->current_position : '' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('employee_id')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    @endif
                </div>

                <!-- Section: Informasi Surat & Penempatan -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Informasi Surat & Rencana Penempatan
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Surat -->
                        <div>
                            <label for="letter_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Surat Penawaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="letter_number" id="letter_number"
                                value="{{ old('letter_number', $suggestedNumber) }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('letter_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Format: Nomor/Bulan Romawi/Tahun/OL</p>
                            @error('letter_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat Penawaran -->
                        <div>
                            <label for="offer_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Penawaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="offer_date" id="offer_date"
                                value="{{ old('offer_date', date('Y-m-d')) }}" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('offer_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('offer_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Rencana Tipe Kontrak -->
                        <div>
                            <label for="contract_type" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Rencana Tipe Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <select name="contract_type" id="contract_type" required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('contract_type') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                                <option value="PKWT" {{ old('contract_type') == 'PKWT' ? 'selected' : '' }}>PKWT
                                    (Perjanjian Kerja Waktu Tertentu)</option>
                                <option value="MT" {{ old('contract_type') == 'MT' ? 'selected' : '' }}>MT (Management
                                    Trainee)</option>
                                <option value="MAGANG" {{ old('contract_type') == 'MAGANG' ? 'selected' : '' }}>MAGANG
                                    (Internship / Pemagangan)</option>
                            </select>
                            @error('contract_type')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Batas Waktu Respon -->
                        <div>
                            <label for="valid_until" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Berlaku Hingga / Batas Respon
                            </label>
                            <input type="date" name="valid_until" id="valid_until"
                                value="{{ old('valid_until', date('Y-m-d', strtotime('+7 days'))) }}" autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('valid_until') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            <p class="text-[10px] text-slate-400 mt-1">Batas waktu kandidat menerima tawaran (opsional)</p>
                            @error('valid_until')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi / Jabatan -->
                        <div>
                            <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Posisi / Jabatan Ditawarkan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                value="{{ old('position', $employee?->current_position) }}" required autocomplete="off"
                                placeholder="Contoh: Staff Akuntansi"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang / Penempatan -->
                        <div>
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Penempatan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $employee?->current_branch) }}" required autocomplete="off"
                                placeholder="Contoh: Kantor Pusat - Jakarta"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Rencana Periode & Kompensasi -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        3. Rencana Masa Kerja & Kompensasi
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Tanggal Mulai Kerja -->
                        <div>
                            <label for="proposed_start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Rencana Mulai Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_start_date" id="proposed_start_date"
                                value="{{ old('proposed_start_date', $employee?->first_join_date ? $employee->first_join_date->format('Y-m-d') : date('Y-m-d')) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_start_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_start_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Selesai Kerja -->
                        <div>
                            <label for="proposed_end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Rencana Selesai Kerja <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_end_date" id="proposed_end_date"
                                value="{{ old('proposed_end_date', date('Y-m-d', strtotime('+1 year -1 day'))) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Gaji Pokok / Uang Saku -->
                        <div>
                            <label for="basic_salary" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Gaji Pokok / Uang Saku (Rp) <span class="text-rose-500">*</span>
                            </label>
                            <input type="number" name="basic_salary" id="basic_salary" min="0" step="1000"
                                value="{{ old('basic_salary') }}" required autocomplete="off"
                                placeholder="Contoh: 5000000"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('basic_salary') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            @error('basic_salary')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tunjangan Tetap -->
                        <div>
                            <label for="allowance" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tunjangan Tetap (Rp) <span class="text-slate-400 font-normal">(Opsional)</span>
                            </label>
                            <input type="number" name="allowance" id="allowance" min="0" step="1000"
                                value="{{ old('allowance', 0) }}" autocomplete="off" placeholder="Contoh: 1000000"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('allowance') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            @error('allowance')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Section: Ketentuan & Catatan -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        4. Ketentuan & Catatan Tambahan
                    </h4>

                    <div class="space-y-4">
                        <!-- Syarat & Ketentuan Khusus -->
                        <div>
                            <label for="terms" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Syarat & Ketentuan Khusus (Opsional)
                            </label>
                            <textarea name="terms" id="terms" rows="3" autocomplete="off"
                                placeholder="Contoh: Masa percobaan (probation) berlaku selama 3 bulan pertama. Fasilitas mencakup BPJS Kesehatan dan Ketenagakerjaan."
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('terms') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">{{ old('terms') }}</textarea>
                            @error('terms')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Catatan Internal -->
                        <div>
                            <label for="notes" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Catatan Internal HR (Opsional)
                            </label>
                            <input type="text" name="notes" id="notes" value="{{ old('notes') }}"
                                autocomplete="off" placeholder="Catatan internal yang tidak dicetak pada surat penawaran"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('notes') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('notes')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="pt-4 border-t border-[#E2E8F0] flex items-center justify-end gap-3">
                    <a href="{{ $employee ? route('employees.show', $employee) : route('offering-letters.index') }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan & Terbitkan Offering Letter</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function fillEmployeeDetails(select) {
            const selected = select.options[select.selectedIndex];
            if (!selected || !selected.value) return;

            const position = selected.getAttribute('data-position');
            const branch = selected.getAttribute('data-branch');
            const startDate = selected.getAttribute('data-start-date');

            const positionInput = document.getElementById('position');
            const branchInput = document.getElementById('branch');
            const startDateInput = document.getElementById('proposed_start_date');

            if (position && positionInput && !positionInput.value) {
                positionInput.value = position;
            }
            if (branch && branchInput && !branchInput.value) {
                branchInput.value = branch;
            }
            if (startDate && startDateInput && !startDateInput.value) {
                startDateInput.value = startDate;
            }
        }
    </script>
@endsection

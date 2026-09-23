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
            <form action="{{ route('offering-letters.update', $offeringLetter) }}" method="POST" class="space-y-6"
                autocomplete="off">
                @csrf
                @method('PUT')

                <!-- Section 1: Karyawan Penerima Penawaran -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        1. Karyawan Penerima Penawaran
                    </h4>

                    <div class="p-4 rounded-xl bg-slate-50 border border-slate-200">
                        <div class="flex items-start justify-between gap-4 pb-3 border-b border-slate-200">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-xl bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center font-bold text-sm shrink-0">
                                    {{ strtoupper(substr($offeringLetter->employee->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h5 class="text-sm font-bold text-[#1C2434]">{{ $offeringLetter->employee->name }}</h5>
                                    <p class="text-xs text-slate-500 font-mono">NIK:
                                        {{ $offeringLetter->employee->ktp_number }}</p>
                                </div>
                            </div>
                            <span
                                class="px-2.5 py-1 rounded-full text-[10px] font-semibold bg-emerald-50 text-emerald-600 border border-emerald-200">
                                Karyawan Terpilih
                            </span>
                        </div>
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-3 text-xs">
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Posisi /
                                    Jabatan</span>
                                <span
                                    class="font-bold text-slate-700">{{ $offeringLetter->employee->current_position ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Cabang /
                                    Lokasi</span>
                                <span
                                    class="font-bold text-slate-700">{{ $offeringLetter->employee->current_branch ?: '-' }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Jenis Kelamin</span>
                                <span
                                    class="font-bold text-slate-700">{{ in_array(strtolower($offeringLetter->employee->gender ?? ''), ['laki-laki', 'male', 'l']) ? 'Laki-laki' : (in_array(strtolower($offeringLetter->employee->gender ?? ''), ['perempuan', 'female', 'p']) ? 'Perempuan' : ($offeringLetter->employee->gender ?: '-')) }}</span>
                            </div>
                            <div>
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tgl Pertama
                                    Masuk</span>
                                <span
                                    class="font-bold text-slate-700">{{ $offeringLetter->employee->first_join_date ? $offeringLetter->employee->first_join_date->format('d M Y') : '-' }}</span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Tempat, Tanggal
                                    Lahir</span>
                                <span class="font-medium text-slate-700">
                                    {{ $offeringLetter->employee->birth_place ? $offeringLetter->employee->birth_place . ($offeringLetter->employee->birth_date ? ', ' . $offeringLetter->employee->birth_date->format('d M Y') : '') : '-' }}
                                </span>
                            </div>
                            <div class="sm:col-span-2">
                                <span class="text-slate-400 block text-[10px] uppercase font-semibold">Alamat</span>
                                <span class="font-medium text-slate-700 truncate block"
                                    title="{{ $offeringLetter->employee->address }}">{{ $offeringLetter->employee->address ?: '-' }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Informasi Surat & Rencana Kontrak -->
                <div>
                    <h4
                        class="text-xs font-bold uppercase tracking-wider text-[#3C50E0] mb-4 pb-2 border-b border-[#E2E8F0]">
                        2. Informasi Surat & Rencana Kontrak
                    </h4>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <!-- Nomor Surat -->
                        <div>
                            <label for="letter_number" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nomor Surat Penawaran <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="letter_number" id="letter_number"
                                value="{{ old('letter_number', $offeringLetter->letter_number) }}" required
                                autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('letter_number') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono outline-hidden transition">
                            @error('letter_number')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Kode -->
                        <div>
                            <label for="kode" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Kode <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="kode" id="kode"
                                value="{{ old('kode', $offeringLetter->kode) }}" required autocomplete="off"
                                placeholder="Contoh: HRD, CKU, OPS"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('kode') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] font-mono uppercase outline-hidden transition">
                            @error('kode')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Surat -->
                        <div>
                            <label for="offer_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Surat <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="offer_date" id="offer_date"
                                value="{{ old('offer_date', $offeringLetter->offer_date->format('Y-m-d')) }}" required
                                autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('offer_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('offer_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="bidang" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Bidang <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="bidang" id="bidang"
                                value="{{ old('bidang', $offeringLetter->bidang) }}" required autocomplete="off"
                                placeholder="Contoh: Operasional, IT, Keuangan"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('bidang') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('bidang')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Posisi / Jabatan -->
                        <div>
                            <label for="position" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Posisi / Jabatan Ditawarkan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="position" id="position"
                                value="{{ old('position', $offeringLetter->position) }}" required autocomplete="off"
                                placeholder="Contoh: Staff Akuntansi"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('position') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('position')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Cabang / Lokasi Kerja -->
                        <div>
                            <label for="branch" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Cabang / Lokasi Penempatan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="branch" id="branch"
                                value="{{ old('branch', $offeringLetter->branch) }}" required autocomplete="off"
                                placeholder="Contoh: Jakarta Pusat"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('branch') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('branch')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Mulai Kontrak -->
                        <div>
                            <label for="proposed_start_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Awal Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_start_date" id="proposed_start_date"
                                value="{{ old('proposed_start_date', $offeringLetter->proposed_start_date->format('Y-m-d')) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_start_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_start_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Tanggal Akhir Kontrak -->
                        <div>
                            <label for="proposed_end_date" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Tanggal Akhir Kontrak <span class="text-rose-500">*</span>
                            </label>
                            <input type="date" name="proposed_end_date" id="proposed_end_date"
                                value="{{ old('proposed_end_date', $offeringLetter->proposed_end_date->format('Y-m-d')) }}"
                                required autocomplete="off"
                                class="w-full px-3.5 py-2 text-xs rounded-xl bg-[#F8FAFC] border @error('proposed_end_date') border-rose-400 @else border-[#E2E8F0] @enderror focus:bg-white focus:border-[#3C50E0] focus:ring-1 focus:ring-[#3C50E0] outline-hidden transition">
                            @error('proposed_end_date')
                                <p class="text-[11px] text-rose-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Nama Atasan -->
                        <div>
                            <label for="supervisor_name" class="block text-xs font-bold text-[#1C2434] mb-1">
                                Nama Atasan <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" name="supervisor_name" id="supervisor_name"
                                value="{{ old('supervisor_name', $offeringLetter->supervisor_name) }}" required
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
                                value="{{ old('supervisor_position', $offeringLetter->supervisor_position) }}" required
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
                                value="{{ old('office_address', $offeringLetter->office_address) }}" required
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
                    <a href="{{ route('offering-letters.show', $offeringLetter) }}"
                        class="px-4 py-2.5 rounded-xl border border-[#E2E8F0] text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="px-5 py-2.5 rounded-xl bg-[#3C50E0] text-white hover:bg-[#2F40BD] text-xs font-bold shadow-lg shadow-[#3C50E0]/25 transition flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dynamic KODE suffix in letter number
            const letterNumberInput = document.getElementById('letter_number');
            const kodeInput = document.getElementById('kode');
            const initialLetterNumber = @json($offeringLetter->letter_number);

            function syncLetterNumberWithKode() {
                if (!letterNumberInput) return;
                const rawKode = kodeInput ? kodeInput.value.trim().toUpperCase() : '';

                let currentNumber = letterNumberInput.value.trim();
                const olPos = currentNumber.indexOf('/OL');
                let base = olPos !== -1 ? currentNumber.substring(0, olPos + 3) : currentNumber;
                if (!base) {
                    base = initialLetterNumber;
                }

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

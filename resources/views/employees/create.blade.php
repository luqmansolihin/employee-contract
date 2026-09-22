@extends('layouts.app')

@section('title', 'Tambah Karyawan Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-xl font-extrabold tracking-tight text-[#1C2434]">
                    <span class="text-slate-400">SI-KONTRAK /</span> TAMBAH EMPLOYEE
                </h1>
            </div>
            <a href="{{ route('employees.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs">
                &larr; Kembali
            </a>
        </div>

        <!-- Main Form Card -->
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf

            <!-- Section: Data Pribadi Karyawan -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-[#1C2434] uppercase tracking-wider">Data Pribadi Karyawan</h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="name"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Budi Santoso" autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('name')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nomor KTP (NIK) -->
                    <div>
                        <label for="ktp_number"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor KTP / NIK (16 Digit) <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="ktp_number" id="ktp_number" value="{{ old('ktp_number') }}" required
                            maxlength="16" pattern="[0-9]{16}" placeholder="Contoh: 3201012345670001" autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm font-mono rounded-lg border {{ $errors->has('ktp_number') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        <p class="text-xs text-slate-400 mt-1">Harus tepat 16 angka numerik sesuai KTP.</p>
                        @error('ktp_number')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jenis Kelamin -->
                    <div>
                        <label for="gender"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jenis Kelamin <span class="text-rose-500">*</span>
                        </label>
                        <select name="gender" id="gender" required autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('gender') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" {{ old('gender') === 'Laki-laki' ? 'selected' : '' }}>Laki-laki
                            </option>
                            <option value="Perempuan" {{ old('gender') === 'Perempuan' ? 'selected' : '' }}>Perempuan
                            </option>
                        </select>
                        @error('gender')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tempat Lahir -->
                    <div>
                        <label for="birth_place"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tempat Lahir <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="birth_place" id="birth_place" value="{{ old('birth_place') }}" required
                            placeholder="Contoh: Jakarta" autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('birth_place') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('birth_place')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Lahir -->
                    <div>
                        <label for="birth_date"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Lahir <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date') }}" required
                            max="{{ now()->subYears(15)->toDateString() }}" autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('birth_date') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('birth_date')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat Lengkap -->
                    <div class="md:col-span-2">
                        <label for="address"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Alamat Lengkap Domisili <span class="text-rose-500">*</span>
                        </label>
                        <textarea name="address" id="address" rows="3" required
                            placeholder="Masukkan nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota..."
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('address') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">{{ old('address') }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit & Actions -->
            <div class="flex flex-col sm:flex-row items-center justify-between gap-3 pt-2">
                <a href="{{ route('employees.index') }}"
                    class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs w-full sm:w-auto text-center">
                    Batal
                </a>

                <div class="flex flex-col sm:flex-row items-center gap-3 w-full sm:w-auto justify-end">
                    <button type="submit" name="next_action" value="offering"
                        class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-[#3C50E0] text-[#3C50E0] hover:bg-[#3C50E0]/10 transition flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                        <span>Simpan & Buat Offering Letter &rarr;</span>
                    </button>

                    <button type="submit"
                        class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm transition flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan Data Karyawan</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

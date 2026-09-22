@extends('layouts.app')

@section('title', 'Ubah Biodata - ' . $employee->name)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Title -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Daftar Karyawan</a>
                    <span>/</span>
                    <a href="{{ route('employees.show', $employee) }}"
                        class="hover:text-[#3C50E0] transition">{{ $employee->name }}</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Ubah Biodata</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#1C2434] tracking-tight">Ubah Biodata Pribadi Karyawan</h1>
                <p class="text-sm text-slate-500 mt-0.5">Perbarui data kependudukan atau alamat domisili karyawan.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('employees.show', $employee) }}"
                    class="px-4 py-2 text-sm font-medium rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs">
                    &larr; Batal & Kembali
                </a>
            </div>
        </div>

        <!-- Main Form Card -->
        <form action="{{ route('employees.update', $employee) }}" method="POST" class="space-y-6" autocomplete="off">
            @csrf
            @method('PUT')

            <!-- Data Pribadi -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-[#1C2434] uppercase tracking-wider">Biodata Pribadi Karyawan</h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="name"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $employee->name) }}"
                            required autocomplete="off"
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
                        <input type="text" name="ktp_number" id="ktp_number"
                            value="{{ old('ktp_number', $employee->ktp_number) }}" required maxlength="16"
                            pattern="[0-9]{16}" autocomplete="off"
                            class="w-full px-4 py-2.5 text-sm font-mono rounded-lg border {{ $errors->has('ktp_number') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
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
                            <option value="Laki-laki"
                                {{ old('gender', $employee->gender) === 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan"
                                {{ old('gender', $employee->gender) === 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
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
                        <input type="text" name="birth_place" id="birth_place"
                            value="{{ old('birth_place', $employee->birth_place) }}" required autocomplete="off"
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
                        <input type="date" name="birth_date" id="birth_date"
                            value="{{ old('birth_date', $employee->birth_date?->format('Y-m-d')) }}" required
                            autocomplete="off"
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
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('address') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">{{ old('address', $employee->address) }}</textarea>
                        @error('address')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Submit & Actions -->
            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('employees.show', $employee) }}"
                    class="px-5 py-2.5 text-sm font-semibold rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    <span>Simpan Perubahan Biodata</span>
            </div>
        </form>
    </div>
@endsection

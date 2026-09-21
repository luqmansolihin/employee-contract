@extends('layouts.app')

@section('title', 'Tambah Karyawan Baru')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Title -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Daftar Karyawan</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Tambah Baru</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#1C2434] tracking-tight">Pendaftaran Karyawan Kontrak Baru</h1>
                <p class="text-sm text-slate-500 mt-0.5">Isi seluruh informasi pribadi dan detail kontrak kerja awal
                    karyawan.</p>
            </div>
            <a href="{{ route('employees.index') }}"
                class="px-4 py-2 text-sm font-medium rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs">
                &larr; Kembali
            </a>
        </div>

        <!-- Main Form Card -->
        <form action="{{ route('employees.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Data Pribadi -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-[#1C2434] uppercase tracking-wider">1. Data Pribadi Karyawan</h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nama Lengkap -->
                    <div class="md:col-span-2">
                        <label for="name"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            placeholder="Contoh: Budi Santoso"
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
                            maxlength="16" pattern="[0-9]{16}" placeholder="Contoh: 3201012345670001"
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
                        <select name="gender" id="gender" required
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

                    <!-- Email & No Telepon -->
                    <div>
                        <label for="email"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Email Karyawan <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                            placeholder="nama@email.com"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">
                        @error('email')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="phone"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            No. Telepon / WhatsApp <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                            placeholder="Contoh: 081234567890"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">
                        @error('phone')
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
                            placeholder="Contoh: Jakarta"
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
                            max="{{ now()->subYears(15)->toDateString() }}"
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

            <!-- Section 2: Data Penempatan & Kontrak Awal -->
            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center gap-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                            </path>
                        </svg>
                    </div>
                    <h2 class="text-sm font-bold text-[#1C2434] uppercase tracking-wider">2. Data Penempatan & Kontrak Awal
                        (Kontrak #1)</h2>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Tipe Kontrak -->
                    <div class="md:col-span-2">
                        <label for="contract_type"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tipe Kontrak Kerja
                        </label>
                        <select name="contract_type" id="contract_type"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">
                            <option value="PKWT" {{ old('contract_type') === 'PKWT' ? 'selected' : '' }}>PKWT
                                (Perjanjian Kerja Waktu Tertentu)</option>
                            <option value="MT" {{ old('contract_type') === 'MT' ? 'selected' : '' }}>MT (Management
                                Trainee)</option>
                            <option value="MAGANG" {{ old('contract_type') === 'MAGANG' ? 'selected' : '' }}>MAGANG
                                (Internship / Pemagangan)</option>
                        </select>
                    </div>

                    <!-- Nomor Kontrak / PKWT -->
                    <div class="md:col-span-2">
                        <label for="contract_number"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Surat Kontrak / PKWT <span class="text-slate-400 font-normal">(Opsional - Otomatis bila
                                dikosongkan)</span>
                        </label>
                        <input type="text" name="contract_number" id="contract_number"
                            value="{{ old('contract_number') }}" placeholder="Contoh: 001/IX/2026/PKWT"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white font-mono">
                        @error('contract_number')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan -->
                    <div>
                        <label for="position"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jabatan / Posisi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="position" id="position" value="{{ old('position') }}" required
                            placeholder="Contoh: Staff IT / Administrasi"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('position') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('position')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cabang -->
                    <div>
                        <label for="branch"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Cabang Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="branch" id="branch" value="{{ old('branch') }}" required
                            placeholder="Contoh: Jakarta Pusat / Bandung"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('branch') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('branch')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai Bergabung / Mulai Kontrak -->
                    <div>
                        <label for="join_date"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Mulai Kontrak <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="join_date" id="join_date"
                            value="{{ old('join_date', now()->toDateString()) }}" required
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('join_date') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('join_date')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Habis Kontrak -->
                    <div>
                        <label for="contract_end_date"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Habis Kontrak <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="contract_end_date" id="contract_end_date"
                            value="{{ old('contract_end_date') }}" required
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('contract_end_date') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        <p class="text-xs text-slate-400 mt-1">Harus tanggal setelah tanggal mulai kontrak.</p>
                        @error('contract_end_date')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Kontrak -->
                    <div class="md:col-span-2">
                        <label for="notes"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Catatan Kontrak <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="2"
                            placeholder="Keterangan tambahan atau klausul khusus kontrak awal..."
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Live Contract Duration Preview Box -->
                    <div id="duration-preview-box"
                        class="md:col-span-2 hidden p-4 rounded-lg bg-[#F7F9FC] border border-[#E2E8F0] text-xs">
                        <p class="font-bold text-[#1C2434] uppercase tracking-wider mb-1">Preview Kalkulasi Kontrak Awal
                        </p>
                        <div class="flex items-center gap-4 text-slate-600 flex-wrap">
                            <span>Total Durasi: <strong id="preview-duration" class="text-slate-900">-</strong></span>
                            <span>&bull;</span>
                            <span>Sisa Waktu: <strong id="preview-remaining" class="text-slate-900">-</strong></span>
                            <span>&bull;</span>
                            <span>Status Prediksi: <span id="preview-badge"
                                    class="px-2 py-0.5 rounded-full font-semibold">-</span></span>
                        </div>
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

                    <button type="submit" name="next_action" value="contract"
                        class="px-6 py-2.5 text-sm font-semibold rounded-lg bg-[#3C50E0] text-white hover:bg-[#2F40BD] shadow-sm transition flex items-center justify-center gap-2 w-full sm:w-auto">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Simpan & Terbitkan Kontrak</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <script>
        function updateContractPreview() {
            const joinInput = document.getElementById('join_date').value;
            const endInput = document.getElementById('contract_end_date').value;
            const box = document.getElementById('duration-preview-box');

            if (!joinInput || !endInput) {
                box.classList.add('hidden');
                return;
            }

            const joinDate = new Date(joinInput);
            const endDate = new Date(endInput);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (endDate <= joinDate) {
                box.classList.add('hidden');
                return;
            }

            box.classList.remove('hidden');

            const totalDays = Math.round((endDate - joinDate) / (1000 * 60 * 60 * 24));
            const months = Math.round(totalDays / 30);
            document.getElementById('preview-duration').textContent = `${months} bulan (${totalDays} hari)`;

            const remainingDays = Math.round((endDate - today) / (1000 * 60 * 60 * 24));
            const badgeEl = document.getElementById('preview-badge');

            if (remainingDays < 0) {
                document.getElementById('preview-remaining').textContent =
                    `Lewat ${Math.abs(remainingDays)} hari yang lalu`;
                badgeEl.textContent = 'Habis Kontrak';
                badgeEl.className =
                    'px-2 py-0.5 rounded-full font-semibold bg-rose-50 text-rose-700 border border-rose-200';
            } else if (remainingDays <= 30) {
                document.getElementById('preview-remaining').textContent = `Sisa ${remainingDays} hari lagi`;
                badgeEl.textContent = 'Segera Habis (< 30 Hari)';
                badgeEl.className =
                    'px-2 py-0.5 rounded-full font-semibold bg-amber-50 text-amber-700 border border-amber-200';
            } else {
                document.getElementById('preview-remaining').textContent = `Sisa ${remainingDays} hari lagi`;
                badgeEl.textContent = 'Aktif';
                badgeEl.className =
                    'px-2 py-0.5 rounded-full font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200';
            }
        }

        document.getElementById('join_date').addEventListener('change', updateContractPreview);
        document.getElementById('contract_end_date').addEventListener('change', updateContractPreview);
        updateContractPreview();
    </script>
@endsection

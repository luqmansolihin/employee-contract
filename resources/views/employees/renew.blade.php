@extends('layouts.app')

@section('title', 'Perpanjang Kontrak - ' . $employee->name)

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Breadcrumb & Header -->
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center gap-2 text-xs text-slate-500 mb-1">
                    <a href="{{ route('employees.index') }}" class="hover:text-[#3C50E0] transition">Daftar Karyawan</a>
                    <span>/</span>
                    <a href="{{ route('employees.show', $employee) }}"
                        class="hover:text-[#3C50E0] transition">{{ $employee->name }}</a>
                    <span>/</span>
                    <span class="text-slate-800 font-medium">Perpanjang Kontrak</span>
                </div>
                <h1 class="text-2xl font-extrabold text-[#1C2434] tracking-tight">Perpanjangan Kontrak Kerja</h1>
                <p class="text-sm text-slate-500 mt-0.5">Buat periode kontrak baru (Kontrak #{{ $nextSequence }}) tanpa
                    menghapus histori kontrak sebelumnya.</p>
            </div>
            <a href="{{ route('employees.show', $employee) }}"
                class="px-4 py-2 text-sm font-medium rounded-lg border border-[#E2E8F0] text-slate-700 hover:bg-[#F7F9FC] transition bg-white shadow-xs">
                &larr; Batal & Kembali
            </a>
        </div>

        <!-- Info Box: Kontrak Sebelumnya yang Sedang Berjalan -->
        @if ($latestContract)
            <div
                class="p-5 rounded-xl bg-[#1C2434] text-white shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="space-y-1">
                    <div class="flex items-center gap-2">
                        <span
                            class="px-2.5 py-0.5 rounded-full text-xs font-semibold bg-white/10 text-indigo-300 border border-white/10">
                            {{ $latestContract->sequence_label }}
                        </span>
                        <span class="text-xs text-slate-400">Kontrak Terakhir</span>
                    </div>
                    <h3 class="text-lg font-bold text-white">{{ $latestContract->position }} &bull; Cabang
                        {{ $latestContract->branch }}</h3>
                    <p class="text-xs text-slate-300">
                        Periode: <strong class="text-white">{{ $latestContract->start_date->format('d M Y') }}</strong> s/d
                        <strong class="text-white">{{ $latestContract->end_date->format('d M Y') }}</strong>
                        (~{{ $latestContract->duration_in_months }} Bulan)
                    </p>
                </div>
                <div
                    class="text-left sm:text-right border-t sm:border-t-0 sm:border-l border-white/10 pt-3 sm:pt-0 sm:pl-5">
                    <p class="text-xs text-slate-400">Status Saat Ini</p>
                    <p class="text-sm font-bold text-amber-400 mt-0.5">{{ $latestContract->remaining_days_text }}</p>
                </div>
            </div>
        @endif

        <!-- Form Perpanjangan -->
        <form action="{{ route('employees.renew.store', $employee) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-white rounded-xl border border-[#E2E8F0] shadow-xs overflow-hidden">
                <div class="px-6 py-4 border-b border-[#E2E8F0] bg-[#F7F9FC] flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <div class="w-7 h-7 rounded-lg bg-[#3C50E0]/10 text-[#3C50E0] flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15">
                                </path>
                            </svg>
                        </div>
                        <h2 class="text-sm font-bold text-[#1C2434] uppercase tracking-wider">
                            Detail Kontrak Perpanjangan (#{{ $nextSequence }})
                        </h2>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#3C50E0]/10 text-[#3C50E0]">
                        Perpanjangan Ke-{{ $nextSequence - 1 }}
                    </span>
                </div>

                <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-5">
                    <!-- Nomor Kontrak Baru -->
                    <div class="md:col-span-2">
                        <label for="contract_number"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Nomor Surat Kontrak Baru / Adendum <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <input type="text" name="contract_number" id="contract_number"
                            value="{{ old('contract_number') }}" placeholder="Contoh: 042/HRD-PKWT/EXT/2026"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">
                        @error('contract_number')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Mulai Perpanjangan -->
                    <div>
                        <label for="start_date"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Mulai Perpanjangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="start_date" id="start_date"
                            value="{{ old('start_date', $suggestedStartDate) }}" required
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('start_date') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        <p class="text-xs text-slate-400 mt-1">Disarankan H+1 setelah kontrak sebelumnya berakhir.</p>
                        @error('start_date')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Tanggal Selesai Perpanjangan -->
                    <div>
                        <label for="end_date"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Tanggal Selesai Perpanjangan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" name="end_date" id="end_date"
                            value="{{ old('end_date', $suggestedEndDate) }}" required
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('end_date') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        @error('end_date')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Jabatan (bisa disesuaikan jika promosi) -->
                    <div>
                        <label for="position"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Jabatan / Posisi <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="position" id="position"
                            value="{{ old('position', $employee->current_position) }}" required
                            placeholder="Contoh: Senior Staff IT"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('position') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        <p class="text-xs text-slate-400 mt-1">Dapat diubah jika ada kenaikan jabatan/promosi.</p>
                        @error('position')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cabang (bisa disesuaikan jika mutasi) -->
                    <div>
                        <label for="branch"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Cabang Penempatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="branch" id="branch"
                            value="{{ old('branch', $employee->current_branch) }}" required placeholder="Contoh: Surabaya"
                            class="w-full px-4 py-2.5 text-sm rounded-lg border {{ $errors->has('branch') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-[#3C50E0]/20' }} focus:ring-2 outline-hidden transition bg-white">
                        <p class="text-xs text-slate-400 mt-1">Dapat diubah jika ada mutasi penempatan cabang.</p>
                        @error('branch')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Catatan Perpanjangan -->
                    <div class="md:col-span-2">
                        <label for="notes"
                            class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            Catatan / Alasan Perpanjangan <span class="text-slate-400 font-normal">(Opsional)</span>
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                            placeholder="Misal: Perpanjangan kontrak 1 tahun berdasarkan hasil penilaian evaluasi kinerja tahunan (Grade A)..."
                            class="w-full px-4 py-2.5 text-sm rounded-lg border border-[#E2E8F0] focus:border-[#3C50E0] focus:ring-2 focus:ring-[#3C50E0]/20 outline-hidden transition bg-white">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="text-xs text-rose-600 mt-1 font-medium">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Live Contract Duration Preview Box -->
                    <div id="duration-preview-box"
                        class="md:col-span-2 hidden p-4 rounded-lg bg-[#F7F9FC] border border-[#E2E8F0] text-xs">
                        <p class="font-bold text-[#1C2434] uppercase tracking-wider mb-1">Preview Kalkulasi Kontrak
                            Perpanjangan</p>
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
                    <span>Simpan Perpanjangan Kontrak</span>
            </div>
        </form>
    </div>

    <script>
        function updateContractPreview() {
            const startInput = document.getElementById('start_date').value;
            const endInput = document.getElementById('end_date').value;
            const box = document.getElementById('duration-preview-box');

            if (!startInput || !endInput) {
                box.classList.add('hidden');
                return;
            }

            const startDate = new Date(startInput);
            const endDate = new Date(endInput);
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            if (endDate <= startDate) {
                box.classList.add('hidden');
                return;
            }

            box.classList.remove('hidden');

            const totalDays = Math.round((endDate - startDate) / (1000 * 60 * 60 * 24));
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

        document.getElementById('start_date').addEventListener('change', updateContractPreview);
        document.getElementById('end_date').addEventListener('change', updateContractPreview);
        updateContractPreview();
    </script>
@endsection

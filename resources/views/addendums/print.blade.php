<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adendum Kontrak - {{ $addendum->addendum_number }} - {{ $addendum->employee->name }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #111827;
            margin: 0;
            padding: 2.5cm 2.5cm;
            background: #fff;
        }

        .no-print-bar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: #1C2434;
            color: white;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-family: system-ui, sans-serif;
            font-size: 13px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .btn-print {
            background: #3C50E0;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 12px;
        }

        .btn-print:hover {
            background: #2F40BD;
        }

        .header-logo {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 12px;
            margin-bottom: 24px;
        }

        .company-name {
            font-size: 16pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }

        .company-sub {
            font-size: 10pt;
            color: #4B5563;
            margin: 4px 0 0 0;
        }

        .doc-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-decoration: underline;
            margin-top: 16px;
            margin-bottom: 4px;
            text-transform: uppercase;
        }

        .doc-number {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 24px;
        }

        .article-title {
            font-weight: bold;
            text-align: center;
            margin-top: 20px;
            margin-bottom: 6px;
            text-transform: uppercase;
        }

        table.change-table {
            width: 100%;
            margin: 12px 0;
            border-collapse: collapse;
        }

        table.change-table th,
        table.change-table td {
            border: 1px solid #374151;
            padding: 6px 10px;
            font-size: 11pt;
        }

        table.change-table th {
            background-color: #F3F4F6;
            text-align: left;
        }

        .signature-block {
            margin-top: 48px;
            display: flex;
            justify-content: space-between;
        }

        .sig-col {
            width: 45%;
            text-align: center;
        }

        .sig-space {
            height: 70px;
        }

        @media print {
            .no-print-bar {
                display: none !important;
            }

            body {
                padding: 1.5cm 1.5cm;
            }
        }
    </style>
</head>

<body>

    <div class="no-print-bar">
        <span>Pratinjau Cetak: <strong>Surat Adendum Perjanjian Kerja ({{ $addendum->sequence_label }})</strong></span>
        <div>
            <button onclick="window.print()" class="btn-print">Cetak / Simpan PDF</button>
            <button onclick="window.close()" class="btn-print"
                style="background: #475569; margin-left: 8px;">Tutup</button>
        </div>
    </div>

    <!-- Letterhead -->
    <div class="header-logo">
        <h1 class="company-name">PT. CIPTA KARYA UTAMA</h1>
        <p class="company-sub">
            {{ $addendum->office_address ?: ($addendum->contract->office_address ?: 'Gedung Perkantoran Sudirman Central, Lantai 12, Jakarta Pusat • Telp: (021) 555-0199 • Email: hrd@ciptakarya.co.id') }}
        </p>
    </div>

    <div class="doc-title">SURAT ADENDUM {{ $addendum->sequence_label }}<br>PERJANJIAN KERJA
        ({{ $addendum->contract->contract_type }})</div>
    <div class="doc-number">Nomor: {{ $addendum->addendum_number }}</div>

    <p>
        Pada hari ini, <strong>{{ Carbon\Carbon::parse($addendum->issue_date)->translatedFormat('l, d F Y') }}</strong>,
        bertempat di Jakarta, telah dibuat dan ditandatangani kesepakatan Adendum Perjanjian Kerja oleh dan antara:
    </p>

    <p style="margin-left: 16px;">
        1. <strong>PT. CIPTA KARYA UTAMA</strong>, diwakili oleh
        {{ $addendum->supervisor_name ?: ($addendum->contract->supervisor_name ?: 'Hendra Wijaya, S.Psi.') }}
        ({{ $addendum->supervisor_position ?: ($addendum->contract->supervisor_position ?: 'HR Manager') }})
        (selanjutnya disebut
        <strong>PIHAK PERTAMA</strong>).<br>
        2. <strong>{{ $addendum->employee->name }}</strong>, Pemegang KTP NIK {{ $addendum->employee->ktp_number }}
        (selanjutnya disebut <strong>PIHAK KEDUA</strong>).
    </p>

    <p>
        Menimbang Perjanjian Kerja Induk Nomor:
        <strong>{{ $addendum->contract->contract_number ?: '#' . $addendum->contract->id }}</strong> tertanggal
        <strong>{{ $addendum->contract->start_date->translatedFormat('d F Y') }}</strong>, PARA PIHAK dengan ini
        sepakat untuk mengadakan perubahan ketentuan (Adendum) dengan kesepakatan sebagai berikut:
    </p>

    <!-- Pasal 1 -->
    <div class="article-title">Pasal 1<br>PERUBAHAN KETENTUAN KONTRAK</div>
    <p>
        PARA PIHAK sepakat mengubah ketentuan dalam Perjanjian Kerja Induk sebagaimana tercantum dalam tabel
        perbandingan di bawah ini:
    </p>

    <table class="change-table">
        <thead>
            <tr>
                <th style="width: 30%;">Hal / Klausul</th>
                <th style="width: 35%;">Ketentuan Sebelumnya</th>
                <th style="width: 35%;">Ketentuan Baru (Adendum)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Masa Berakhir Kontrak</strong></td>
                <td>{{ $addendum->previous_end_date->translatedFormat('d F Y') }}</td>
                <td><strong>{{ $addendum->new_end_date->translatedFormat('d F Y') }}</strong> (Efektif sejak
                    {{ $addendum->effective_date->translatedFormat('d F Y') }})</td>
            </tr>
            @if ($addendum->new_position && $addendum->new_position != $addendum->previous_position)
                <tr>
                    <td><strong>Jabatan / Posisi</strong></td>
                    <td>{{ $addendum->previous_position }}</td>
                    <td><strong>{{ $addendum->new_position }}</strong></td>
                </tr>
            @endif
            @if ($addendum->new_salary && $addendum->new_salary != $addendum->previous_salary)
                <tr>
                    <td><strong>Gaji Pokok / Upah</strong></td>
                    <td>{{ $addendum->formatted_previous_salary ?: 'Rp 0' }}</td>
                    <td><strong>{{ $addendum->formatted_new_salary }}</strong></td>
                </tr>
            @endif
        </tbody>
    </table>

    <p>
        <strong>Pokok Alasan Perubahan:</strong><br>
        {{ $addendum->amendment_reason }}
    </p>

    @if ($addendum->clause_changes)
        <p><strong>Rincian Perubahan Klausul:</strong></p>
        <div style="margin-left: 16px;">
            {!! nl2br(e($addendum->clause_changes)) !!}
        </div>
    @endif

    <!-- Pasal 2 -->
    <div class="article-title">Pasal 2<br>KETENTUAN LAIN-LAIN</div>
    <p>
        1. Seluruh ketentuan dan pasal-pasal dalam Perjanjian Kerja Induk yang tidak diubah dalam Adendum ini dinyatakan
        tetap berlaku sah dan mengikat PARA PIHAK.<br>
        2. Surat Adendum ini merupakan bagian yang tidak terpisahkan dari Perjanjian Kerja Induk Nomor:
        {{ $addendum->contract->contract_number ?: $addendum->contract->id }}.<br>
        3. Adendum ini dibuat dalam rangkap 2 (dua) yang masing-masing bermaterai cukup dan memiliki kekuatan hukum yang
        sama.
    </p>

    <!-- Signatures -->
    <div class="signature-block">
        <div class="sig-col">
            <p>PIHAK KEDUA,<br>Karyawan</p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
                {{ $addendum->employee->name }}</p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">NIK: {{ $addendum->employee->ktp_number }}</p>
        </div>

        <div class="sig-col">
            <p>PIHAK PERTAMA,<br><strong>PT. CIPTA KARYA UTAMA</strong></p>
            <div class="sig-space"></div>
            <p style="text-decoration: underline; font-weight: bold; margin-bottom: 2px;">
                {{ $addendum->supervisor_name ?: ($addendum->contract->supervisor_name ?: 'Hendra Wijaya, S.Psi.') }}
            </p>
            <p style="font-size: 10pt; color: #4B5563; margin-top: 0;">
                {{ $addendum->supervisor_position ?: ($addendum->contract->supervisor_position ?: 'HR Manager') }}
            </p>
        </div>
    </div>

</body>

</html>

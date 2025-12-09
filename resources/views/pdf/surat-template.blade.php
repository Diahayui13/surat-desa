<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Surat {{ $surat->jenis_surat }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12pt; }
        .header { text-align: center; font-weight: bold; font-size: 18px; }
        .content { margin-top: 20px; }
        .ttd { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        PEMERINTAH DESA DIGITAL<br>
        SURAT {{ strtoupper($surat->jenis_surat) }}
    </div>

    <div class="content">
        <p>Nomor Surat: {{ $surat->id }}/DS/{{ date('Y') }}</p>
        <p>Nama: {{ $surat->nama_warga }}</p>
        <p>Isi Surat: {{ $surat->isi ?? '-' }}</p>
    </div>

    <div class="ttd">
        <p>Mengetahui,</p>
        <p>Kepala Desa</p>
        @if ($surat->ttd_path)
            <img src="{{ public_path('storage/' . $surat->ttd_path) }}" alt="TTD" height="80">
        @endif
        <p><strong>{{ auth()->user()->name }}</strong></p>
    </div>
</body>
</html>

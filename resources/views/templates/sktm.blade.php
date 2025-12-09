<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan Tidak Mampu</title>
    <style>
        @page {
            size: A4;
            margin: 2cm;
        }
        
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
        }
        
        .kop-surat {
            text-align: center;
            border-bottom: 3px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        
        .kop-surat img {
            height: 80px;
            margin-bottom: 10px;
        }
        
        .kop-surat h1 {
            font-size: 16pt;
            font-weight: bold;
            margin: 5px 0;
            text-transform: uppercase;
        }
        
        .kop-surat h2 {
            font-size: 14pt;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }
        
        .kop-surat h3 {
            font-size: 13pt;
            font-weight: bold;
            margin: 3px 0;
            text-transform: uppercase;
        }
        
        .kop-surat p {
            font-size: 10pt;
            margin: 2px 0;
        }
        
        .judul {
            text-align: center;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            font-size: 14pt;
        }
        
        .nomor {
            text-align: center;
            margin-bottom: 20px;
            font-size: 11pt;
        }
        
        .isi-surat {
            text-align: justify;
            margin-bottom: 15px;
        }
        
        .data-table {
            width: 100%;
            margin: 20px 0;
        }
        
        .data-table td {
            padding: 3px 0;
            vertical-align: top;
        }
        
        .data-table td:first-child {
            width: 200px;
        }
        
        .data-table td:nth-child(2) {
            width: 20px;
            text-align: center;
        }
        
        .ttd-section {
            margin-top: 40px;
            text-align: right;
        }
        
        .ttd-container {
            display: inline-block;
            text-align: center;
            min-width: 250px;
        }
        
        .ttd-tempat {
            margin-bottom: 10px;
        }
        
        .ttd-jabatan {
            font-weight: bold;
            margin-bottom: 60px;
        }
        
        .ttd-image {
            max-height: 80px;
            margin: -50px 0 -10px 0;
        }
        
        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            margin-top: 5px;
        }
        
        @media print {
            body {
                margin: 0;
                padding: 0;
            }
            
            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    
    <!-- Kop Surat -->
    <div class="kop-surat">
        @if(file_exists(public_path('images/logo-desa.png')))
            <img src="{{ public_path('images/logo-desa.png') }}" alt="Logo Desa">
        @endif
        <h1>PEMERINTAH KABUPATEN BOJONEGORO</h1>
        <h2>KECAMATAN DANDER</h2>
        <h3>DESA SUMBERTLASEH</h3>
        <p>Sekretariat Jalan Balai Desa No.97 Sumbertlaseh</p>
    </div>
    
    <!-- Judul Surat -->
    <div class="judul">
        SURAT KETERANGAN TIDAK MAMPU
    </div>
    
    <!-- Nomor Surat -->
    <div class="nomor">
        No: {{ $surat->nomor_surat ?? '___/___/___/' . date('Y') }}
    </div>
    
    <!-- Isi Surat -->
    <div class="isi-surat">
        <p>Yang bertanda tangan di bawah ini :</p>
        
        <table class="data-table">
            <tr>
                <td>N a m a</td>
                <td>:</td>
                <td>{{ $surat->pejabat_penanda_tangan ?? 'Kepala Desa Sumbertlaseh' }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $surat->jabatan_penanda_tangan ?? 'Kepala Desa' }}</td>
            </tr>
        </table>
        
        <p>Menerangkan dengan sesungguhnya bahwa :</p>
        
        <table class="data-table">
            <tr>
                <td>N a m a</td>
                <td>:</td>
                <td><strong>{{ $surat->nama_pemohon }}</strong></td>
            </tr>
            <tr>
                <td>Tempat/Tgl.Lahir</td>
                <td>:</td>
                <td>{{ $surat->tempat_lahir ?? '-' }}, {{ $surat->tanggal_lahir ? $surat->tanggal_lahir->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $surat->jenis_kelamin ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pekerjaan</td>
                <td>:</td>
                <td>{{ $surat->pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Alamat</td>
                <td>:</td>
                <td>{{ $surat->alamat ?? '-' }}</td>
            </tr>
        </table>
        
        <p>Orang tersebut diatas adalah benar-benar Penduduk Desa Sumbertlaseh Kecamatan Dander Kabupaten Bojonegoro Propinsi Jawa Timur, yang perekonomiannya kurang/tidak mampu.</p>
        
        @if($surat->nama_anak)
        <p>Surat Keterangan ini diberikan untuk Pengajuan Keringanan Biaya Sekolah/Pendidikan <strong>Anaknya</strong> :</p>
        
        <table class="data-table">
            <tr>
                <td>N a m a</td>
                <td>:</td>
                <td><strong>{{ $surat->nama_anak }}</strong></td>
            </tr>
            <tr>
                <td>NIS / NIM</td>
                <td>:</td>
                <td>{{ $surat->nis_nim ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tempat / Tgl. Lahir</td>
                <td>:</td>
                <td>{{ $surat->tempat_lahir_anak ?? '-' }}, {{ $surat->tanggal_lahir_anak ? $surat->tanggal_lahir_anak->format('d-m-Y') : '-' }}</td>
            </tr>
            <tr>
                <td>Jenis Kelamin</td>
                <td>:</td>
                <td>{{ $surat->jenis_kelamin_anak ?? '-' }}</td>
            </tr>
            <tr>
                <td>Pendidikan di</td>
                <td>:</td>
                <td>{{ $surat->pendidikan ?? '-' }}</td>
            </tr>
            <tr>
                <td>Jurusan/Kelas/Semester</td>
                <td>:</td>
                <td>{{ $surat->jurusan_kelas ?? '-' }}</td>
            </tr>
        </table>
        @endif
        
        <p>Demikian surat keterangan ini dibuat untuk dipergunakan sebagaimana mestinya.</p>
    </div>
    
    <!-- Tanda Tangan -->
    <div class="ttd-section">
        <div class="ttd-container">
            <div class="ttd-tempat">
                Sumbertlaseh, {{ now()->locale('id')->isoFormat('D MMMM Y') }}
            </div>
            <div class="ttd-jabatan">
                {{ $surat->jabatan_penanda_tangan ?? 'Kepala Desa' }}
            </div>
            
            @if($surat->tanda_tangan)
                <img src="{{ public_path('storage/' . $surat->tanda_tangan) }}" alt="TTD" class="ttd-image">
            @else
                <div style="height: 80px;"></div>
            @endif
            
            <div class="ttd-nama">
                {{ $surat->pejabat_penanda_tangan ?? 'NAMA KEPALA DESA' }}
            </div>
        </div>
    </div>
    
    <!-- Toolbar untuk Preview (hidden saat print) -->
    <div class="no-print" style="position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%); background: white; padding: 15px 30px; border-radius: 50px; box-shadow: 0 4px 20px rgba(0,0,0,0.2); display: flex; gap: 15px;">
        <button onclick="window.print()" style="background: #245BCA; color: white; border: none; padding: 10px 25px; border-radius: 25px; cursor: pointer; font-weight: 600;">
            <i class="fa-solid fa-print"></i> Cetak
        </button>
        <a href="{{ route('admin.template.download-pdf', $surat->id) }}" style="background: #00BF63; color: white; border: none; padding: 10px 25px; border-radius: 25px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block;">
            <i class="fa-solid fa-download"></i> Download PDF
        </a>
        <a href="{{ route('admin.ttddigital') }}" style="background: #6c757d; color: white; border: none; padding: 10px 25px; border-radius: 25px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block;">
            <i class="fa-solid fa-arrow-left"></i> Kembali
        </a>
    </div>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</body>
</html>
@extends('layouts.admin')

@section('content')
<div class="min-h-screen bg-gray-100 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Toolbar -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-4 flex justify-between items-center">
            <a href="{{ route('admin.ttddigital') }}" class="text-gray-600 hover:text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-arrow-left"></i>
                Kembali
            </a>
            <div class="flex gap-2">
                <button onclick="window.print()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-print"></i>
                    Cetak Surat
                </button>
                <button onclick="downloadPDF()" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition flex items-center gap-2">
                    <i class="fa-solid fa-download"></i>
                    Download PDF
                </button>
            </div>
        </div>

        <!-- Surat Content -->
        <div id="surat-content" class="bg-white rounded-lg shadow-lg p-12" style="min-height: 29.7cm;">
            <!-- Kop Surat -->
            <div class="text-center border-b-4 border-black pb-4 mb-6">
                <div class="flex items-center justify-center gap-4 mb-2">
                    <img src="{{ asset('images/logo-desa.png') }}" alt="Logo Desa" class="h-20" onerror="this.style.display='none'">
                    <div>
                        <h1 class="text-xl font-bold uppercase">PEMERINTAH DESA {{ strtoupper(config('app.nama_desa', 'NAMA DESA')) }}</h1>
                        <h2 class="text-lg font-semibold">KECAMATAN {{ strtoupper(config('app.kecamatan', 'NAMA KECAMATAN')) }}</h2>
                        <h3 class="text-md">KABUPATEN {{ strtoupper(config('app.kabupaten', 'NAMA KABUPATEN')) }}</h3>
                        <p class="text-sm mt-1">Alamat: {{ config('app.alamat_desa', 'Alamat Lengkap Desa') }}</p>
                    </div>
                </div>
            </div>

            <!-- Nomor Surat -->
            <div class="text-center mb-6">
                <p class="text-sm">Nomor: {{ $surat->nomor_surat ?? '___/___/___/' . date('Y') }}</p>
            </div>

            <!-- Judul Surat -->
            <h2 class="text-center text-xl font-bold underline mb-6">
                {{ strtoupper($surat->jenis_surat ?? 'SURAT KETERANGAN DOMISILI') }}
            </h2>

            <!-- Pembuka -->
            <div class="mb-6 text-justify leading-relaxed">
                <p class="mb-4">Yang bertanda tangan di bawah ini Kepala Desa {{ config('app.nama_desa', 'Nama Desa') }}, Kecamatan {{ config('app.kecamatan', 'Nama Kecamatan') }}, Kabupaten {{ config('app.kabupaten', 'Nama Kabupaten') }}, dengan ini menerangkan bahwa:</p>
            </div>

            <!-- Data Pemohon -->
            <div class="mb-6">
                <table class="w-full text-sm">
                    <tr>
                        <td class="py-1 w-1/4">Nama</td>
                        <td class="py-1 w-8">:</td>
                        <td class="py-1 font-semibold">{{ $surat->nama_pemohon ?? $surat->user->name }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">NIK</td>
                        <td class="py-1">:</td>
                        <td class="py-1 font-semibold">{{ $surat->nik_pemohon ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Tempat, Tanggal Lahir</td>
                        <td class="py-1">:</td>
                        <td class="py-1">{{ $surat->tempat_lahir ?? '-' }}, {{ $surat->tanggal_lahir ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Jenis Kelamin</td>
                        <td class="py-1">:</td>
                        <td class="py-1">{{ $surat->jenis_kelamin ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1">Pekerjaan</td>
                        <td class="py-1">:</td>
                        <td class="py-1">{{ $surat->pekerjaan ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="py-1 align-top">Alamat</td>
                        <td class="py-1 align-top">:</td>
                        <td class="py-1">{{ $surat->alamat ?? '-' }}</td>
                    </tr>
                </table>
            </div>

            <!-- Isi Surat -->
            <div class="mb-6 text-justify leading-relaxed">
                <p class="mb-4">Adalah benar warga Desa {{ config('app.nama_desa', 'Nama Desa') }} dan berdomisili di alamat tersebut di atas.</p>
                
                <p class="mb-4">Surat keterangan ini dibuat untuk keperluan: <strong>{{ $surat->keperluan ?? $surat->keterangan }}</strong></p>
                
                <p class="mb-4">Demikian surat keterangan ini dibuat dengan sebenarnya, untuk dapat dipergunakan sebagaimana mestinya.</p>
            </div>

            <!-- Tanggal & TTD -->
            <div class="mt-12 flex justify-end">
                <div class="text-center" style="width: 300px;">
                    <p class="mb-1">{{ config('app.nama_desa', 'Nama Desa') }}, {{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMMM Y') }}</p>
                    <p class="font-semibold mb-16">Kepala Desa</p>
                    
                    @if($surat->tanda_tangan)
                        <div class="flex justify-center mb-2">
                            <img src="{{ asset('storage/' . $surat->tanda_tangan) }}" 
                                 alt="Tanda Tangan" 
                                 class="h-24 object-contain">
                        </div>
                    @else
                        <div class="mb-16"></div>
                    @endif
                    
                    <p class="font-bold border-t-2 border-black pt-1 inline-block px-8">
                        {{ config('app.nama_kepala_desa', 'NAMA KEPALA DESA') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    @media print {
        body * {
            visibility: hidden;
        }
        #surat-content, #surat-content * {
            visibility: visible;
        }
        #surat-content {
            position: absolute;
            left: 0;
            top: 0;
            width: 21cm;
            padding: 2cm;
            margin: 0;
            box-shadow: none;
        }
        .no-print {
            display: none !important;
        }
    }
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
<script>
    function downloadPDF() {
        const element = document.getElementById('surat-content');
        const opt = {
            margin: 1,
            filename: 'Surat_{{ $surat->jenis_surat }}_{{ $surat->nama_pemohon }}.pdf',
            image: { type: 'jpeg', quality: 0.98 },
            html2canvas: { scale: 2, useCORS: true },
            jsPDF: { unit: 'cm', format: 'a4', orientation: 'portrait' }
        };
        
        html2pdf().set(opt).from(element).save();
    }
</script>
@endsection
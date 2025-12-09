@extends('layouts.admin')

@php
    $__fallbackFromSurat = false;

    if (empty($surats) || (is_iterable($surats) && count($surats) === 0)) {
        $sQuery = \App\Models\Surat::with('user')->orderByDesc('created_at')->get();

        $total    = $total    ?? $sQuery->count();
        $menunggu = $menunggu ?? $sQuery->where('status', 'Menunggu')->count();
        $proses   = $proses   ?? $sQuery->where('status', 'Diproses')->count();
        $selesai  = $selesai  ?? $sQuery->where('status', 'Selesai')->count();

        $surats = $sQuery->map(function ($x) {
            return (object)[
                'id'                 => $x->id,
                'nama_pemohon'       => $x->nama_pemohon ?? optional($x->user)->name,
                'nik_pemohon'        => $x->nik_pemohon ?? null,
                'jenis_surat'        => $x->jenis_surat ?? $x->judul,
                'judul'              => $x->judul,
                'tanggal_pengajuan'  => $x->tanggal_pengajuan ?? $x->created_at,
                'created_at'         => $x->created_at,
                'status'             => $x->status,
                'tanggal_diproses'   => $x->tanggal_diproses,
                'tanggal_selesai'    => $x->tanggal_selesai,
            ];
        });

        $__fallbackFromSurat = true;
    }
@endphp

@section('content')
<div class="min-h-screen bg-gray-50 p-4">
    <div class="flex items-center justify-between mb-4">
        <h1 class="text-lg font-bold text-gray-800">Permohonan Masuk</h1>
    </div>
    <p class="text-sm text-gray-500 mb-4">Daftar pengajuan surat oleh warga yang menunggu verifikasi</p>

    <!-- Statistik -->
    <div class="grid grid-cols-4 gap-2 mb-6">
        <button onclick="filterStatus('total')" id="btn-total" class="filter-btn bg-white rounded-xl shadow p-3 text-center hover:shadow-lg transition active-filter">
            <div class="text-blue-600 font-semibold text-lg">{{ $total }}</div>
            <div class="text-gray-500 text-xs">Total</div>
        </button>
        <button onclick="filterStatus('menunggu')" id="btn-menunggu" class="filter-btn bg-white rounded-xl shadow p-3 text-center hover:shadow-lg transition">
            <div class="text-yellow-500 font-semibold text-lg">{{ $menunggu }}</div>
            <div class="text-gray-500 text-xs">Menunggu</div>
        </button>
        <button onclick="filterStatus('diproses')" id="btn-diproses" class="filter-btn bg-white rounded-xl shadow p-3 text-center hover:shadow-lg transition">
            <div class="text-green-500 font-semibold text-lg">{{ $proses }}</div>
            <div class="text-gray-500 text-xs">Proses</div>
        </button>
        <button onclick="filterStatus('selesai')" id="btn-selesai" class="filter-btn bg-white rounded-xl shadow p-3 text-center hover:shadow-lg transition">
            <div class="text-blue-500 font-semibold text-lg">{{ $selesai }}</div>
            <div class="text-gray-500 text-xs">Selesai</div>
        </button>
    </div>

    <div id="loading" class="hidden text-center py-4">
        <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500"></div>
        <p class="text-sm text-gray-500 mt-2">Memuat data...</p>
    </div>

    <!-- Daftar Surat -->
    <div id="surat-list" class="space-y-3 pb-24">
        @foreach ($surats as $s)
            <div class="bg-white rounded-xl shadow p-4 surat-item">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">{{ $s->nama_pemohon ?? 'Nama tidak tersedia' }}</h3>
                        <p class="text-sm text-gray-600">{{ $s->jenis_surat ?? $s->judul }}</p>
                        <p class="text-xs text-gray-400 mt-1">
                            <i class="fa-regular fa-calendar mr-1"></i>
                            {{ $s->tanggal_pengajuan ? $s->tanggal_pengajuan->format('d M Y, H:i') : ($s->created_at ? $s->created_at->format('d M Y') : '-') }}
                        </p>
                        @if($s->nik_pemohon)
                            <p class="text-xs text-gray-500">NIK: {{ $s->nik_pemohon }}</p>
                        @endif
                    </div>

                    <!-- Status badge -->
                    <div>
                        @if ($s->status === 'Menunggu')
                             <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Menunggu</span>
                        @elseif ($s->status === 'Diproses')
                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Diproses</span>
                        @elseif ($s->status === 'Selesai')
                            <span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">Selesai</span>
                        @endif
                    </div>
                </div>

                <!-- Timeline -->
                @if($s->status !== 'Menunggu')
                <div class="mt-3 pt-3 border-t border-gray-100">
                    <div class="flex items-center text-xs text-gray-500 space-x-3">
                        @if($s->tanggal_diproses)
                            <div class="flex items-center">
                                <i class="fa-solid fa-hourglass-start text-green-500 mr-1"></i>
                                <span>Diproses: {{ $s->tanggal_diproses->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                        @if($s->tanggal_selesai)
                            <div class="flex items-center">
                                <i class="fa-solid fa-check-circle text-blue-500 mr-1"></i>
                                <span>Selesai: {{ $s->tanggal_selesai->format('d/m/Y H:i') }}</span>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="flex gap-2 mt-3">
                    <a href="{{ route('admin.surat.detail', $s->id) }}" 
                       class="flex-1 text-center text-xs bg-blue-100 text-blue-700 px-4 py-2 rounded-lg shadow-sm hover:bg-blue-200 transition">
                        <i class="fa-regular fa-eye mr-1"></i> Lihat Detail
                    </a>
                    
                    {{-- ✅ Tombol PROSES (Menunggu) → Redirect ke Upload TTD --}}
                    @if($s->status === 'Menunggu')
                        <a href="{{ route('admin.tanda.tangan', $s->id) }}" 
                           class="flex-1 text-center text-xs bg-green-100 text-green-700 px-4 py-2 rounded-lg shadow-sm hover:bg-green-200 transition">
                            <i class="fa-solid fa-play mr-1"></i> Proses
                        </a>
                    {{-- ✅ Tombol SELESAIKAN (Diproses) → Update status ke Selesai --}}
                    @elseif($s->status === 'Diproses')
                        <form action="{{ route('admin.surat.update-status', $s->id) }}" method="POST" class="flex-1">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="Selesai">
                            <button type="submit" class="w-full text-xs bg-blue-100 text-blue-700 px-4 py-2 rounded-lg shadow-sm hover:bg-blue-200 transition">
                                <i class="fa-solid fa-check mr-1"></i> Selesaikan
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endforeach
    </div>

    <div id="no-data" class="hidden text-center py-8">
        <i class="fa-regular fa-folder-open text-gray-300 text-5xl mb-3"></i>
        <p class="text-gray-500">Tidak ada surat yang sesuai</p>
    </div>

    <!-- Search bar -->
    <div class="fixed bottom-0 left-0 md:left-64 right-0 bg-white border-t p-3 shadow-lg z-10">
        <div class="max-w-5xl mx-auto">
            <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                <i class="fa-solid fa-magnifying-glass text-gray-400 mr-2"></i>
                <input type="text" id="search-input" placeholder="Cari nama / NIK pemohon..." 
                       class="bg-transparent flex-grow text-sm outline-none" autocomplete="off">
                <button id="clear-search" class="hidden text-gray-400 hover:text-gray-600 ml-2">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
.active-filter { border: 2px solid #ec4899; background: linear-gradient(135deg, #fce7f3 0%, #ffffff 100%); }
.surat-item { animation: fadeIn 0.3s ease-in; }
@keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>

<script>
let currentStatus = 'total';
let searchTimeout = null;

function filterStatus(status) {
    currentStatus = status;
    document.querySelectorAll('.filter-btn').forEach(btn => btn.classList.remove('active-filter'));
    document.getElementById('btn-' + status).classList.add('active-filter');
    loadData();
}

document.getElementById('search-input').addEventListener('input', function(e) {
    const val = e.target.value;
    document.getElementById('clear-search').classList.toggle('hidden', val.length === 0);
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => loadData(), 500);
});

document.getElementById('clear-search').addEventListener('click', function() {
    document.getElementById('search-input').value = '';
    this.classList.add('hidden');
    loadData();
});

function loadData() {
    const search = document.getElementById('search-input').value;
    document.getElementById('loading').classList.remove('hidden');
    document.getElementById('surat-list').style.opacity = '0.5';
    
    fetch(`{{ route('admin.permohonan') }}?status=${currentStatus}&search=${search}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        updateSuratList(data.surats);
        updateStats(data.stats);
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('surat-list').style.opacity = '1';
    })
    .catch(e => {
        console.error(e);
        document.getElementById('loading').classList.add('hidden');
        document.getElementById('surat-list').style.opacity = '1';
    });
}

function updateSuratList(surats) {
    const container = document.getElementById('surat-list');
    const noData = document.getElementById('no-data');
    
    if (surats.length === 0) {
        container.innerHTML = '';
        noData.classList.remove('hidden');
        return;
    }
    
    noData.classList.add('hidden');
    let html = '';
    
    surats.forEach(s => {
        const badge = getStatusBadge(s.status);
        const date = s.tanggal_pengajuan ? formatDate(s.tanggal_pengajuan) : (s.created_at ? formatDate(s.created_at) : '-');
        const timeline = s.status !== 'Menunggu' ? getTimelineHtml(s) : '';
        const actions = getActionButtons(s);
        
        html += `
            <div class="bg-white rounded-xl shadow p-4 surat-item">
                <div class="flex justify-between items-start mb-2">
                    <div class="flex-1">
                        <h3 class="font-semibold text-gray-800">${s.nama_pemohon || 'Nama tidak tersedia'}</h3>
                        <p class="text-sm text-gray-600">${s.jenis_surat || s.judul}</p>
                        <p class="text-xs text-gray-400 mt-1"><i class="fa-regular fa-calendar mr-1"></i>${date}</p>
                        ${s.nik_pemohon ? `<p class="text-xs text-gray-500">NIK: ${s.nik_pemohon}</p>` : ''}
                    </div>
                    <div>${badge}</div>
                </div>
                ${timeline}
                <div class="flex gap-2 mt-3">${actions}</div>
            </div>
        `;
    });
    
    container.innerHTML = html;
}

function updateStats(stats) {
    document.querySelector('#btn-total .font-semibold').textContent = stats.total;
    document.querySelector('#btn-menunggu .font-semibold').textContent = stats.menunggu;
    document.querySelector('#btn-diproses .font-semibold').textContent = stats.proses;
    document.querySelector('#btn-selesai .font-semibold').textContent = stats.selesai;
}

function getStatusBadge(status) {
    const badges = {
        'Menunggu': '<span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-medium">Menunggu</span>',
        'Diproses': '<span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-medium">Diproses</span>',
        'Selesai': '<span class="bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">Selesai</span>'
    };
    return badges[status] || '';
}

function formatDate(dateString) {
    const d = new Date(dateString);
    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    return `${d.getDate()} ${months[d.getMonth()]} ${d.getFullYear()}, ${String(d.getHours()).padStart(2, '0')}:${String(d.getMinutes()).padStart(2, '0')}`;
}

function getTimelineHtml(s) {
    let html = '<div class="mt-3 pt-3 border-t border-gray-100"><div class="flex items-center text-xs text-gray-500 space-x-3">';
    if (s.tanggal_diproses) {
        html += `<div class="flex items-center"><i class="fa-solid fa-hourglass-start text-green-500 mr-1"></i><span>Diproses: ${formatDate(s.tanggal_diproses)}</span></div>`;
    }
    if (s.tanggal_selesai) {
        html += `<div class="flex items-center"><i class="fa-solid fa-check-circle text-blue-500 mr-1"></i><span>Selesai: ${formatDate(s.tanggal_selesai)}</span></div>`;
    }
    html += '</div></div>';
    return html;
}

function getActionButtons(s) {
    const csrf = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let btns = `<a href="/admin/surat/${s.id}" class="flex-1 text-center text-xs bg-blue-100 text-blue-700 px-4 py-2 rounded-lg shadow-sm hover:bg-blue-200 transition"><i class="fa-regular fa-eye mr-1"></i> Lihat Detail</a>`;

    if (s.status === 'Menunggu') {
        btns += `<a href="/admin/tanda-tangan/${s.id}" class="flex-1 text-center text-xs bg-green-100 text-green-700 px-4 py-2 rounded-lg shadow-sm hover:bg-green-200 transition"><i class="fa-solid fa-play mr-1"></i> Proses</a>`;
    } else if (s.status === 'Diproses') {
        btns += `
            <form action="/admin/surat/${s.id}/update-status" method="POST" class="flex-1">
                <input type="hidden" name="_token" value="${csrf}">
                <input type="hidden" name="_method" value="PATCH">
                <input type="hidden" name="status" value="Selesai">
                <button type="submit" class="w-full text-xs bg-blue-100 text-blue-700 px-4 py-2 rounded-lg shadow-sm hover:bg-blue-200 transition">
                    <i class="fa-solid fa-check mr-1"></i> Selesaikan
                </button>
            </form>
        `;
    }

    return btns;
}
</script>

@if($__fallbackFromSurat)
<script>
(function () {
  const LOCAL = {!! json_encode($surats, JSON_UNESCAPED_UNICODE) !!};
  const MAP = { menunggu: 'Menunggu', diproses: 'Diproses', selesai: 'Selesai' };

  function computeStats(arr) {
    return {
      total: arr.length,
      menunggu: arr.filter(x => x.status === 'Menunggu').length,
      proses: arr.filter(x => x.status === 'Diproses').length,
      selesai: arr.filter(x => x.status === 'Selesai').length,
    };
  }

  window.loadData = function () {
    const q = (document.getElementById('search-input').value || '').toLowerCase();
    let rows = [...LOCAL];

    if (currentStatus !== 'total') {
      rows = rows.filter(x => x.status === MAP[currentStatus]);
    }
    if (q) {
      rows = rows.filter(x =>
        (x.nama_pemohon && x.nama_pemohon.toLowerCase().includes(q)) ||
        (x.nik_pemohon && String(x.nik_pemohon).toLowerCase().includes(q))
      );
    }

    updateSuratList(rows);
    updateStats(computeStats(LOCAL));
    document.getElementById('loading').classList.add('hidden');
    document.getElementById('surat-list').style.opacity = '1';
  };

  document.addEventListener('DOMContentLoaded', function () {
    updateStats(computeStats(LOCAL));
    window.loadData();
  });
})();
</script>
@endif

@endsection
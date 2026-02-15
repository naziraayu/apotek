@extends('layouts.template')

@section('content')
<!--! ================================================================ !-->
<!--! [Start] Main Content !-->
<!--! ================================================================ !-->
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Monitoring Kadaluarsa Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('obat.index') }}">Master Obat</a></li>
                    <li class="breadcrumb-item active">Kadaluarsa</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('obat.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                        <a href="{{ route('obat.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Obat</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <!-- Alert Info -->
                @if(isset($stats) && ($stats['sudah_kadaluarsa'] > 0 || $stats['kadaluarsa_30_hari'] > 0))
                <div class="col-lg-12">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="feather-alert-octagon fs-4 me-3"></i>
                            <div>
                                <strong>Peringatan Kadaluarsa!</strong>
                                @if($stats['sudah_kadaluarsa'] > 0)
                                    <span class="fw-bold">{{ $stats['sudah_kadaluarsa'] }} obat sudah kadaluarsa!</span>
                                @endif
                                @if($stats['kadaluarsa_30_hari'] > 0)
                                    <span>{{ $stats['kadaluarsa_30_hari'] }} obat akan kadaluarsa dalam 30 hari.</span>
                                @endif
                                Segera lakukan penanganan!
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                </div>
                @endif

                <!-- Statistik Card -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted mb-2">Sudah Kadaluarsa</div>
                                    <h3 class="fw-bold mb-0 text-danger">{{ isset($stats) ? $stats['sudah_kadaluarsa'] : 0 }}</h3>
                                    <small class="text-danger">Harus dimusnahkan</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                                    <i class="feather-x-octagon"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted mb-2">Kadaluarsa ≤ 30 Hari</div>
                                    <h3 class="fw-bold mb-0 text-danger">{{ isset($stats) ? $stats['kadaluarsa_30_hari'] : 0 }}</h3>
                                    <small class="text-danger">Sangat mendesak</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                                    <i class="feather-alert-triangle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted mb-2">Kadaluarsa ≤ 60 Hari</div>
                                    <h3 class="fw-bold mb-0 text-warning">{{ isset($stats) ? $stats['kadaluarsa_60_hari'] : 0 }}</h3>
                                    <small class="text-warning">Perlu perhatian</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                    <i class="feather-alert-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted mb-2">Kadaluarsa ≤ 90 Hari</div>
                                    <h3 class="fw-bold mb-0 text-info">{{ isset($stats) ? $stats['kadaluarsa_90_hari'] : 0 }}</h3>
                                    <small class="text-muted">Monitor rutin</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-info text-info">
                                    <i class="feather-info"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Obat Kadaluarsa & Akan Kadaluarsa</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('obat.kadaluarsa') }}" class="avatar-text avatar-xs bg-warning">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('obat.kadaluarsa') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Kategori Filter -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Kategori</label>
                                            <select name="kategori_id" class="form-select form-select-sm">
                                                <option value="">Semua Kategori</option>
                                                @foreach(\App\Models\KategoriObat::all() as $kategori)
                                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Status Filter -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Kadaluarsa</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>Sudah Kadaluarsa</option>
                                                <option value="30_days" {{ request('status') == '30_days' ? 'selected' : '' }}>≤ 30 Hari</option>
                                                <option value="60_days" {{ request('status') == '60_days' ? 'selected' : '' }}>≤ 60 Hari</option>
                                                <option value="90_days" {{ request('status') == '90_days' ? 'selected' : '' }}>≤ 90 Hari</option>
                                            </select>
                                        </div>

                                        <!-- Urutan Filter -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Urutkan</label>
                                            <select name="sort" class="form-select form-select-sm">
                                                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Terdekat</option>
                                                <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Terjauh</option>
                                            </select>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-4 col-sm-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('obat.kadaluarsa') }}" class="btn btn-sm btn-light">
                                                    <i class="feather-refresh-cw me-1"></i>Reset
                                                </a>
                                                <button type="button" class="btn btn-sm btn-success" onclick="window.print()">
                                                    <i class="feather-printer me-1"></i>Cetak
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                                    <strong>Berhasil!</strong> {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="kadaluarsaTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Kategori</th>
                                            <th>No. Batch</th>
                                            <th>Stok</th>
                                            <th>Tanggal Kadaluarsa</th>
                                            <th>Sisa Hari</th>
                                            <th>Status</th>
                                            <th>Tindakan</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obats as $index => $obat)
                                            @php
                                                // PERBAIKAN: Hitung sisa hari dengan benar menggunakan Carbon
                                                if ($obat->tanggal_kadaluarsa) {
                                                    $today = \Carbon\Carbon::today();
                                                    $expiryDate = \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->startOfDay();
                                                    
                                                    // Hitung selisih hari (positif = belum kadaluarsa, negatif = sudah kadaluarsa)
                                                    $sisaHari = $today->diffInDays($expiryDate, false);
                                                    
                                                    // Debug: uncomment untuk debugging
                                                    // dd([
                                                    //     'obat' => $obat->nama_obat,
                                                    //     'today' => $today->format('Y-m-d'),
                                                    //     'expiry' => $expiryDate->format('Y-m-d'),
                                                    //     'sisa_hari' => $sisaHari,
                                                    //     'is_expired' => $obat->isExpired()
                                                    // ]);
                                                } else {
                                                    $sisaHari = null;
                                                }
                                                
                                                // Tentukan class row dan status
                                                if ($obat->isExpired()) {
                                                    $rowClass = 'table-dark';
                                                    $statusBadge = 'bg-dark';
                                                    $statusText = 'Kadaluarsa';
                                                    $statusIcon = 'x-octagon';
                                                    $tindakan = 'Musnahkan segera';
                                                } elseif ($sisaHari !== null && $sisaHari <= 30) {
                                                    $rowClass = 'table-danger';
                                                    $statusBadge = 'bg-danger';
                                                    $statusText = 'Sangat Kritis';
                                                    $statusIcon = 'alert-triangle';
                                                    $tindakan = 'Diskon/Promo besar';
                                                } elseif ($sisaHari !== null && $sisaHari <= 60) {
                                                    $rowClass = 'table-warning';
                                                    $statusBadge = 'bg-warning';
                                                    $statusText = 'Kritis';
                                                    $statusIcon = 'alert-circle';
                                                    $tindakan = 'Prioritas jual';
                                                } else {
                                                    $rowClass = 'table-info';
                                                    $statusBadge = 'bg-info';
                                                    $statusText = 'Perhatian';
                                                    $statusIcon = 'info';
                                                    $tindakan = 'Monitor rutin';
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $obats->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <i class="feather-{{ $statusIcon }} me-2 {{ $obat->isExpired() ? 'text-dark' : ($sisaHari <= 30 ? 'text-danger' : ($sisaHari <= 60 ? 'text-warning' : 'text-info')) }}"></i>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $obat->nama_obat }}</div>
                                                            <small class="fs-12 text-muted">{{ $obat->satuan }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary">
                                                        {{ $obat->kategori->nama_kategori ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($obat->no_batch)
                                                        <code class="fs-12">{{ $obat->no_batch }}</code>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $obat->stok == 0 ? 'bg-secondary' : 'bg-soft-success text-success' }}">
                                                        {{ $obat->stok }} {{ $obat->satuan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($obat->tanggal_kadaluarsa)
                                                        <div>
                                                            <div class="fw-bold">{{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('d M Y') }}</div>
                                                            <small class="text-muted">{{ \Carbon\Carbon::parse($obat->tanggal_kadaluarsa)->format('l') }}</small>
                                                        </div>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($sisaHari === null)
                                                        <span class="text-muted">-</span>
                                                    @elseif($obat->isExpired())
                                                        <span class="badge bg-dark">
                                                            <i class="feather-x me-1"></i>Lewat {{ abs($sisaHari) }} hari
                                                        </span>
                                                    @else
                                                        <div>
                                                            <span class="badge {{ $statusBadge }}">
                                                                {{ $sisaHari }} hari lagi
                                                            </span>
                                                            @if($sisaHari <= 7)
                                                                <div class="progress mt-1" style="height: 4px;">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"></div>
                                                                </div>
                                                            @elseif($sisaHari <= 30)
                                                                <div class="progress mt-1" style="height: 4px;">
                                                                    <div class="progress-bar bg-danger" role="progressbar" style="width: {{ 100 - (($sisaHari - 7) / 23 * 60) }}%"></div>
                                                                </div>
                                                            @elseif($sisaHari <= 60)
                                                                <div class="progress mt-1" style="height: 4px;">
                                                                    <div class="progress-bar bg-warning" role="progressbar" style="width: {{ 40 - (($sisaHari - 30) / 30 * 40) }}%"></div>
                                                                </div>
                                                            @endif
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $statusBadge }}">
                                                        <i class="feather-{{ $statusIcon }} me-1"></i>{{ $statusText }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <small class="fw-bold {{ $obat->isExpired() ? 'text-dark' : ($sisaHari <= 30 ? 'text-danger' : ($sisaHari <= 60 ? 'text-warning' : 'text-info')) }}">
                                                        {{ $tindakan }}
                                                    </small>
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('obat.show', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('obat.edit', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        @if($obat->isExpired())
                                                            <button type="button" class="avatar-text avatar-md text-danger" data-bs-toggle="tooltip" title="Musnahkan" onclick="confirmDestroy({{ $obat->id }}, '{{ $obat->nama_obat }}')">
                                                                <i class="feather-trash-2"></i>
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-check-circle fs-1 text-success"></i>
                                                        <p class="mt-3 text-muted">Tidak ada obat yang akan kadaluarsa</p>
                                                        <p class="text-muted fs-13">Semua obat dalam kondisi baik</p>
                                                        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-primary mt-2">
                                                            <i class="feather-list me-2"></i>Lihat Semua Obat
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <span class="text-muted fs-13">
                                        Menampilkan {{ $obats->firstItem() ?? 0 }} sampai {{ $obats->lastItem() ?? 0 }} dari {{ $obats->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    {{ $obats->links('pagination::bootstrap-4') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Legend Card -->
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <h6 class="card-title mb-3">Keterangan Status</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-dark me-2">Kadaluarsa</span>
                                        <small class="text-muted">Sudah lewat tanggal kadaluarsa - Harus dimusnahkan</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-danger me-2">Sangat Kritis</span>
                                        <small class="text-muted">≤ 30 hari - Diskon besar/Promo</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-warning me-2">Kritis</span>
                                        <small class="text-muted">≤ 60 hari - Prioritas penjualan</small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="d-flex align-items-center">
                                        <span class="badge bg-info me-2">Perhatian</span>
                                        <small class="text-muted">≤ 90 hari - Monitor rutin</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

</main>
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->

<!-- Modal Konfirmasi Pemusnahan -->
<div class="modal fade" id="destroyModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="feather-alert-triangle me-2"></i>Konfirmasi Pemusnahan Obat
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="feather-info me-2"></i>
                    <strong>Perhatian!</strong> Tindakan ini akan menghapus obat yang sudah kadaluarsa.
                </div>
                <p>Apakah Anda yakin ingin memusnahkan obat <strong id="obatName"></strong>?</p>
                <p class="text-muted fs-13">Pastikan Anda telah melakukan dokumentasi pemusnahan sesuai prosedur.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                <form id="destroyForm" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="feather-trash-2 me-2"></i>Ya, Musnahkan
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* Table Row Highlight */
.table-dark {
    background-color: rgba(52, 58, 64, 0.15) !important;
}

.table-danger {
    background-color: rgba(220, 53, 69, 0.1) !important;
}

.table-warning {
    background-color: rgba(255, 193, 7, 0.08) !important;
}

.table-info {
    background-color: rgba(13, 202, 240, 0.05) !important;
}

/* Badge Styling */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Code styling for batch number */
code {
    background-color: #f8f9fa;
    padding: 0.2rem 0.4rem;
    border-radius: 0.25rem;
    font-size: 0.875rem;
}

/* Progress Bar */
.progress {
    background-color: #e9ecef;
    border-radius: 0.25rem;
}

/* Table Hover Effect */
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Avatar Text Hover */
.avatar-text {
    transition: all 0.2s ease;
}

.avatar-text:hover {
    transform: translateY(-2px);
    background-color: rgba(0, 0, 0, 0.05);
}

/* Blinking animation for critical items */
@keyframes blink-danger {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.7; }
}

.table-danger.blink {
    animation: blink-danger 2s infinite;
}

/* Print Styles */
@media print {
    .page-header-right,
    .card-header-action,
    .p-3.border-bottom.bg-light,
    .card-footer,
    .hstack.gap-2,
    .alert,
    .btn,
    .modal {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
        page-break-inside: avoid;
    }

    .table {
        font-size: 11px;
    }

    .table thead {
        background-color: #f8f9fa !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    .badge {
        border: 1px solid #000;
        color: #000 !important;
        background-color: transparent !important;
    }

    /* Print header */
    @page {
        margin: 2cm;
    }

    .main-content::before {
        content: "LAPORAN OBAT KADALUARSA";
        display: block;
        text-align: center;
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 20px;
    }
}

/* Icon sizing */
.feather-x-octagon,
.feather-alert-triangle,
.feather-alert-circle,
.feather-info {
    font-size: 18px;
}

/* Stats Card Animation */
.card-body {
    transition: transform 0.2s ease;
}

.card:hover .card-body {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Add blinking effect to expired items
    setInterval(function() {
        $('.table-dark, .table-danger').toggleClass('blink');
    }, 2000);

    // Count and log statistics
    var expiredCount = $('.table-dark').length;
    var criticalCount = $('.table-danger').length;
    var warningCount = $('.table-warning').length;
    var infoCount = $('.table-info').length;

    console.log('Monitoring Kadaluarsa:');
    console.log('- Sudah Kadaluarsa: ' + expiredCount);
    console.log('- Sangat Kritis (≤30 hari): ' + criticalCount);
    console.log('- Kritis (≤60 hari): ' + warningCount);
    console.log('- Perhatian (≤90 hari): ' + infoCount);

    // Auto-sort by expiry date
    if (expiredCount > 0) {
        console.warn('⚠️ Perhatian: Terdapat ' + expiredCount + ' obat yang sudah kadaluarsa!');
    }
});

// Function to confirm destroy expired medicine
function confirmDestroy(id, name) {
    $('#obatName').text(name);
    $('#destroyForm').attr('action', '/obat/' + id);
    var modal = new bootstrap.Modal(document.getElementById('destroyModal'));
    modal.show();
}
</script>
@endpush
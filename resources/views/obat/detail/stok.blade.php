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
                    <h5 class="m-b-10">Monitoring Stok Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('obat.index') }}">Master Obat</a></li>
                    <li class="breadcrumb-item active">Monitoring Stok</li>
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
                @if(isset($stats) && $stats['total_minimum'] > 0)
                <div class="col-lg-12">
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="feather-alert-triangle fs-4 me-3"></i>
                            <div>
                                <strong>Perhatian!</strong> 
                                Terdapat <strong>{{ $stats['total_minimum'] }}</strong> obat dengan stok minimum atau habis. 
                                @if($stats['stok_habis'] > 0)
                                    <span class="text-danger fw-bold">{{ $stats['stok_habis'] }} obat stok habis!</span>
                                @endif
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
                                    <div class="text-muted mb-2">Total Produk</div>
                                    <h3 class="fw-bold mb-0">{{ $obats->total() }}</h3>
                                    <small class="text-muted">Semua obat terdaftar</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                    <i class="feather-package"></i>
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
                                    <div class="text-muted mb-2">Stok Habis</div>
                                    <h3 class="fw-bold mb-0 text-danger">{{ isset($stats) ? $stats['stok_habis'] : 0 }}</h3>
                                    <small class="text-danger">Prioritas tertinggi</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                                    <i class="feather-x-circle"></i>
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
                                    <div class="text-muted mb-2">Stok Kritis</div>
                                    <h3 class="fw-bold mb-0 text-danger">{{ isset($stats) ? $stats['stok_kritis'] : 0 }}</h3>
                                    <small class="text-warning">Stok < 50% minimum</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-danger text-danger">
                                    <i class="feather-trending-down"></i>
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
                                    <div class="text-muted mb-2">Stok Minimum</div>
                                    <h3 class="fw-bold mb-0 text-warning">{{ isset($stats) ? $stats['perlu_restock'] : 0 }}</h3>
                                    <small class="text-muted">Perlu restock</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                    <i class="feather-alert-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Stok Obat</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('obat.stok') }}" class="avatar-text avatar-xs bg-warning">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('obat.stok') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Kategori Filter -->
                                        <div class="col-md-4 col-sm-6">
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
                                            <label class="form-label text-muted fs-12 mb-1">Filter Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="habis" {{ request('status') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                                                <option value="kritis" {{ request('status') == 'kritis' ? 'selected' : '' }}>Stok Kritis</option>
                                                <option value="minimum" {{ request('status') == 'minimum' ? 'selected' : '' }}>Stok Minimum</option>
                                            </select>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-5 col-sm-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('obat.stok') }}" class="btn btn-sm btn-light">
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
                                <table class="table table-hover" id="stokTable">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th>Stok Saat Ini</th>
                                            <th>Stok Minimum</th>
                                            <th>Selisih</th>
                                            <th>Persentase</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obats as $index => $obat)
                                            @php
                                                $selisih = $obat->stok - $obat->stok_minimum;
                                                $persentase = $obat->stok_minimum > 0 ? ($obat->stok / $obat->stok_minimum) * 100 : 0;
                                                
                                                // Tentukan class row
                                                $rowClass = '';
                                                if ($obat->stok == 0) {
                                                    $rowClass = 'table-danger';
                                                } elseif ($persentase < 50) {
                                                    $rowClass = 'table-warning';
                                                } elseif ($obat->isStokMinimum()) {
                                                    $rowClass = 'table-info';
                                                }
                                            @endphp
                                            <tr class="{{ $rowClass }}">
                                                <td>{{ $obats->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        @if($obat->stok == 0)
                                                            <i class="feather-alert-triangle text-danger me-2"></i>
                                                        @elseif($persentase < 50)
                                                            <i class="feather-alert-circle text-danger me-2"></i>
                                                        @elseif($obat->isStokMinimum())
                                                            <i class="feather-info text-warning me-2"></i>
                                                        @endif
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $obat->nama_obat }}</div>
                                                            @if($obat->no_batch)
                                                                <small class="fs-12 text-muted">Batch: {{ $obat->no_batch }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary">
                                                        {{ $obat->kategori->nama_kategori ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ $obat->satuan }}</td>
                                                <td>
                                                    <div class="d-flex flex-column">
                                                        <span class="badge {{ $obat->stok == 0 ? 'bg-danger' : ($persentase < 50 ? 'bg-warning' : ($obat->isStokMinimum() ? 'bg-info' : 'bg-success')) }}">
                                                            {{ $obat->stok }} {{ $obat->satuan }}
                                                        </span>
                                                        <!-- Progress Bar -->
                                                        <div class="progress mt-1" style="height: 5px;">
                                                            <div class="progress-bar {{ $obat->stok == 0 ? 'bg-danger' : ($persentase < 50 ? 'bg-warning' : ($persentase <= 100 ? 'bg-info' : 'bg-success')) }}" 
                                                                 role="progressbar" 
                                                                 style="width: {{ min($persentase, 100) }}%"
                                                                 aria-valuenow="{{ $persentase }}" 
                                                                 aria-valuemin="0" 
                                                                 aria-valuemax="100">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-secondary text-secondary">
                                                        {{ $obat->stok_minimum }} {{ $obat->satuan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($selisih < 0)
                                                        <span class="badge bg-soft-danger text-danger">
                                                            {{ $selisih }} {{ $obat->satuan }}
                                                        </span>
                                                    @elseif($selisih == 0)
                                                        <span class="badge bg-soft-warning text-warning">
                                                            Pas Minimum
                                                        </span>
                                                    @else
                                                        <span class="badge bg-soft-success text-success">
                                                            +{{ $selisih }} {{ $obat->satuan }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge {{ $persentase == 0 ? 'bg-danger' : ($persentase < 50 ? 'bg-warning' : ($persentase <= 100 ? 'bg-info' : 'bg-success')) }}">
                                                        {{ number_format($persentase, 0) }}%
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($obat->stok == 0)
                                                        <span class="badge bg-danger">
                                                            <i class="feather-x-circle me-1"></i>Habis
                                                        </span>
                                                    @elseif($persentase < 50)
                                                        <span class="badge bg-danger">
                                                            <i class="feather-alert-triangle me-1"></i>Kritis
                                                        </span>
                                                    @elseif($obat->isStokMinimum())
                                                        <span class="badge bg-warning">
                                                            <i class="feather-alert-circle me-1"></i>Minimum
                                                        </span>
                                                    @else
                                                        <span class="badge bg-success">
                                                            <i class="feather-check-circle me-1"></i>Aman
                                                        </span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('obat.show', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('obat.edit', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-inbox fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data obat</p>
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
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

</main>
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->
@endsection

@push('styles')
<style>
/* Table Row Highlight */
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

/* Print Styles */
@media print {
    .page-header-right,
    .card-header-action,
    .p-3.border-bottom.bg-light,
    .card-footer,
    .hstack.gap-2,
    .alert,
    .btn {
        display: none !important;
    }

    .card {
        border: none !important;
        box-shadow: none !important;
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
}

/* Stats Card Animation */
.card-body {
    transition: transform 0.2s ease;
}

.card:hover .card-body {
    transform: translateY(-2px);
}

/* Icon alignment */
.feather-alert-triangle,
.feather-alert-circle,
.feather-info {
    font-size: 18px;
}

/* Custom badge colors */
.bg-soft-secondary {
    background-color: rgba(108, 117, 125, 0.1);
}

.text-secondary {
    color: #6c757d !important;
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

    // Highlight rows based on stock status
    highlightCriticalStock();

    // Add sorting functionality to table headers
    $('th').css('cursor', 'pointer');

    // Count and display stock alerts
    var habisCount = $('.table-danger').length;
    var kritisCount = $('.table-warning').length;
    var minimumCount = $('.table-info').length;

    console.log('Monitoring Stok:');
    console.log('- Habis: ' + habisCount);
    console.log('- Kritis: ' + kritisCount);
    console.log('- Minimum: ' + minimumCount);
});

function highlightCriticalStock() {
    // Add blinking effect to critical stock rows
    setInterval(function() {
        $('.table-danger').toggleClass('pulse-danger');
    }, 2000);
}

// Auto refresh setiap 5 menit (optional)
// setInterval(function() {
//     location.reload();
// }, 300000);
</script>
@endpush
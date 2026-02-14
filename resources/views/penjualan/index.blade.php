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
                    <h5 class="m-b-10">Data Penjualan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active">Penjualan</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('penjualan.laporan') }}" class="btn btn-secondary">
                            <i class="feather-file-text me-2"></i>
                            <span>Laporan</span>
                        </a>
                        <a href="{{ route('penjualan.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Transaksi Baru</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <!-- Statistik Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Penjualan</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                    <i class="feather-shopping-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Diskon</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-success text-success">
                                    <i class="feather-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Bulan Ini</h6>
                                    <h3 class="fw-bold mb-0">{{ $penjualanBulanIni }}</h3>
                                    <small class="text-muted">transaksi</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-info text-info">
                                    <i class="feather-calendar"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Belum Lunas</h6>
                                    <h3 class="fw-bold mb-0">{{ $penjualanBelumLunas }}</h3>
                                    <small class="text-muted">transaksi</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                    <i class="feather-alert-circle"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Penjualan</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('penjualan.index') }}" class="avatar-text avatar-xs bg-warning">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('penjualan.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Status Pembayaran -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Pembayaran</label>
                                            <select name="status_pembayaran" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="lunas" {{ request('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                                <option value="belum_lunas" {{ request('status_pembayaran') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                            </select>
                                        </div>

                                        <!-- Metode Pembayaran -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Metode Pembayaran</label>
                                            <select name="metode_pembayaran" class="form-select form-select-sm">
                                                <option value="">Semua Metode</option>
                                                <option value="tunai" {{ request('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                                <option value="transfer" {{ request('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                                <option value="kartu_kredit" {{ request('metode_pembayaran') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                                                <option value="e-wallet" {{ request('metode_pembayaran') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                            </select>
                                        </div>

                                        <!-- Pelanggan -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Pelanggan</label>
                                            <select name="pelanggan_id" class="form-select form-select-sm">
                                                <option value="">Semua Pelanggan</option>
                                                @foreach($pelanggans as $pelanggan)
                                                    <option value="{{ $pelanggan->id }}" {{ request('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                                        {{ $pelanggan->nama_pelanggan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Tanggal Mulai -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Tanggal Mulai</label>
                                            <input type="date" name="tanggal_mulai" class="form-control form-control-sm" value="{{ request('tanggal_mulai') }}">
                                        </div>

                                        <!-- Tanggal Akhir -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Tanggal Akhir</label>
                                            <input type="date" name="tanggal_akhir" class="form-control form-control-sm" value="{{ request('tanggal_akhir') }}">
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-2 col-sm-6">
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="feather-search me-1"></i>Filter
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-light">
                                                <i class="feather-refresh-cw me-1"></i>Reset Filter
                                            </a>
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

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <!-- Search Bar -->
                            <div class="px-4 pt-3">
                                <div class="row">
                                    <div class="col-md-6">
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteSelected" style="display: none;">
                                            <i class="feather-trash-2 me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                                        </button>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="input-group input-group-sm">
                                            <input type="text" class="form-control" id="searchInput" placeholder="Cari no nota, pelanggan...">
                                            <button class="btn btn-primary" type="button">
                                                <i class="feather-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="penjualanTable">
                                    <thead>
                                        <tr>
                                            <th class="wd-30">
                                                <div class="btn-group">
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input" id="checkAll">
                                                        <label class="custom-control-label" for="checkAll"></label>
                                                    </div>
                                                </div>
                                            </th>
                                            <th>No</th>
                                            <th>No Nota</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Total Item</th>
                                            <th>Grand Total</th>
                                            <th>Pembayaran</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penjualan as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $item->id }}" value="{{ $item->id }}" {{ $item->status_pembayaran === 'lunas' ? 'disabled' : '' }}>
                                                        <label class="custom-control-label" for="customCheck{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $penjualan->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold text-primary">{{ $item->no_nota }}</div>
                                                    <small class="text-muted">{{ $item->kasir_nama }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $item->tanggal_penjualan->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $item->tanggal_penjualan->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                                            {{ strtoupper(substr($item->pelanggan_nama, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $item->pelanggan_nama }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item->total_item }} item
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary">{{ $item->grand_total_format }}</div>
                                                    @if($item->diskon > 0)
                                                        <small class="text-success">Diskon: {{ $item->diskon_format }}</small>
                                                    @endif
                                                </td>
                                                <td>{!! $item->metode_pembayaran_badge !!}</td>
                                                <td>{!! $item->status_pembayaran_badge !!}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('penjualan.show', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('penjualan.cetak', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Cetak Nota">
                                                            <i class="feather-printer"></i>
                                                        </a>
                                                        @if($item->status_pembayaran === 'belum_lunas')
                                                            <a href="{{ route('penjualan.edit', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="feather-edit"></i>
                                                            </a>
                                                            <form action="{{ route('penjualan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="avatar-text avatar-md text-danger" data-bs-toggle="tooltip" title="Hapus" style="border: none; background: none;">
                                                                    <i class="feather-trash-2"></i>
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-shopping-bag fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data penjualan</p>
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
                                        Menampilkan {{ $penjualan->firstItem() ?? 0 }} sampai {{ $penjualan->lastItem() ?? 0 }} dari {{ $penjualan->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($penjualan->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $penjualan->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $penjualan->lastPage()) as $page)
                                            @if ($page == $penjualan->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $penjualan->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($penjualan->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $penjualan->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
                                            </li>
                                        @else
                                            <li class="page-item disabled">
                                                <span class="page-link">›</span>
                                            </li>
                                        @endif
                                    </ul>
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
.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}

.custom-control-label::before,
.custom-control-label::after {
    top: 0.15rem;
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.avatar-text {
    transition: all 0.2s ease;
}

.avatar-text:hover {
    transform: translateY(-2px);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#penjualanTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Check All Checkbox
    $('#checkAll').on('change', function() {
        $('.row-checkbox:not(:disabled)').prop('checked', this.checked);
        toggleDeleteButton();
    });

    // Individual Checkbox
    $(document).on('change', '.row-checkbox', function() {
        var allChecked = $('.row-checkbox:not(:disabled)').length === $('.row-checkbox:checked').length;
        $('#checkAll').prop('checked', allChecked);
        toggleDeleteButton();
    });

    // Toggle Delete Button
    function toggleDeleteButton() {
        var checkedCount = $('.row-checkbox:checked').length;
        $('#selectedCount').text(checkedCount);
        if (checkedCount > 0) {
            $('#deleteSelected').fadeIn();
        } else {
            $('#deleteSelected').fadeOut();
        }
    }

    // Delete Selected
    $('#deleteSelected').on('click', function() {
        var ids = [];
        $('.row-checkbox:checked').each(function() {
            ids.push($(this).val());
        });
        
        if (confirm(`Yakin ingin menghapus ${ids.length} data penjualan terpilih?`)) {
            $.ajax({
                url: '{{ route("penjualan.destroyAll") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message || 'Gagal menghapus data');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan');
                }
            });
        }
    });

    // Delete Single Item with Confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        if (confirm('Yakin ingin menghapus data penjualan ini?')) {
            this.submit();
        }
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
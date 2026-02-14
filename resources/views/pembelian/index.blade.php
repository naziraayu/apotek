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
                    <h5 class="m-b-10">Data Pembelian</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Transaksi</li>
                    <li class="breadcrumb-item active">Pembelian</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('pembelian.laporan') }}" class="btn btn-secondary">
                            <i class="feather-file-text me-2"></i>
                            <span>Laporan</span>
                        </a>
                        <a href="{{ route('pembelian.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Pembelian</span>
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
                                    <h6 class="text-muted mb-2">Total Pembelian</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                    <i class="feather-shopping-cart"></i>
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
                                    <i class="feather-tag"></i>
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
                                    <h3 class="fw-bold mb-0">{{ $pembelianBulanIni }}</h3>
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
                                    <h6 class="text-muted mb-2">Pending</h6>
                                    <h3 class="fw-bold mb-0">{{ $pembelianPending }}</h3>
                                    <small class="text-muted">transaksi</small>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                    <i class="feather-clock"></i>
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
                            <h5 class="card-title">Daftar Pembelian</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('pembelian.index') }}" class="avatar-text avatar-xs bg-warning" data-bs-toggle="tooltip" title="Refresh">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('pembelian.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Status -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                                <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </div>

                                        <!-- Supplier -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Supplier</label>
                                            <select name="supplier_id" class="form-select form-select-sm">
                                                <option value="">Semua Supplier</option>
                                                @foreach($suppliers as $supplier)
                                                    <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                        {{ $supplier->nama_supplier }}
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

                                        <!-- Bulan -->
                                        <div class="col-md-1 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Bulan</label>
                                            <select name="bulan" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $i)->format('M') }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Tahun -->
                                        <div class="col-md-1 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Tahun</label>
                                            <select name="tahun" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @for($year = date('Y'); $year >= 2020; $year--)
                                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-1 col-sm-6">
                                            <button type="submit" class="btn btn-sm btn-primary w-100">
                                                <i class="feather-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <div class="col-12">
                                            <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-light">
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
                                            <input type="text" class="form-control" id="searchInput" placeholder="Cari no nota, supplier...">
                                            <button class="btn btn-primary" type="button">
                                                <i class="feather-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="pembelianTable">
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
                                            <th>Supplier</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Diskon</th>
                                            <th>Grand Total</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pembelian as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $pembelian->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold text-primary">{{ $item->no_nota }}</div>
                                                    <small class="text-muted">{{ $item->user_nama }}</small>
                                                </td>
                                                <td>
                                                    <div>{{ $item->tanggal_pembelian->format('d/m/Y') }}</div>
                                                    <small class="text-muted">{{ $item->tanggal_pembelian->format('H:i') }}</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                                            {{ strtoupper(substr($item->supplier_nama, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $item->supplier_nama }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item->total_item }} item
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->total_harga_format }}</div>
                                                </td>
                                                <td>
                                                    <div class="text-success">{{ $item->diskon_format }}</div>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-primary">{{ $item->grand_total_format }}</div>
                                                </td>
                                                <td>{!! $item->status_badge !!}</td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('pembelian.show', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('pembelian.cetak', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Cetak">
                                                            <i class="feather-printer"></i>
                                                        </a>
                                                        @if($item->status === 'pending')
                                                            <a href="{{ route('pembelian.edit', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="feather-edit"></i>
                                                            </a>
                                                            <form action="{{ route('pembelian.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
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
                                                <td colspan="11" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-shopping-cart fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data pembelian</p>
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
                                        Menampilkan {{ $pembelian->firstItem() ?? 0 }} sampai {{ $pembelian->lastItem() ?? 0 }} dari {{ $pembelian->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($pembelian->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $pembelian->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $pembelian->lastPage()) as $page)
                                            @if ($page == $pembelian->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $pembelian->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($pembelian->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $pembelian->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
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
        $('#pembelianTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Check All Checkbox
    $('#checkAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        toggleDeleteButton();
    });

    // Individual Checkbox
    $(document).on('change', '.row-checkbox', function() {
        var allChecked = $('.row-checkbox').length === $('.row-checkbox:checked').length;
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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} data pembelian terpilih?`)) {
            $.ajax({
                url: '{{ route("pembelian.destroyAll") }}',
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
        
        if (confirm('Yakin ingin menghapus data pembelian ini?')) {
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
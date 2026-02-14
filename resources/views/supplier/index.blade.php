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
                    <h5 class="m-b-10">Data Supplier</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Supplier</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Supplier</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Supplier</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('supplier.index') }}" class="avatar-text avatar-xs bg-warning" data-bs-toggle="tooltip" title="Refresh">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('supplier.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Status Supplier -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Supplier</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="aktif" {{ request('status') == 'aktif' ? 'selected' : '' }}>Aktif (6 Bulan Terakhir)</option>
                                                <option value="transaksi" {{ request('status') == 'transaksi' ? 'selected' : '' }}>Pernah Transaksi</option>
                                                <option value="belum_transaksi" {{ request('status') == 'belum_transaksi' ? 'selected' : '' }}>Belum Transaksi</option>
                                            </select>
                                        </div>

                                        <!-- Kota -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Kota</label>
                                            <select name="kota" class="form-select form-select-sm">
                                                <option value="">Semua Kota</option>
                                                @foreach($kotaList as $kota)
                                                    <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>{{ $kota }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Periode Registrasi -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Bulan</label>
                                            <select name="bulan" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @for($i = 1; $i <= 12; $i++)
                                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                    </option>
                                                @endfor
                                            </select>
                                        </div>

                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Tahun</label>
                                            <select name="tahun" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                @for($year = date('Y'); $year >= 2020; $year--)
                                                    <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                                @endfor
                                            </select>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-2 col-sm-6">
                                            <div class="d-flex flex-column gap-2">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('supplier.index') }}" class="btn btn-sm btn-light">
                                                    <i class="feather-refresh-cw me-1"></i>Reset
                                                </a>
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
                                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, kota, telepon...">
                                            <button class="btn btn-primary" type="button">
                                                <i class="feather-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="supplierTable">
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
                                            <th>Nama Supplier</th>
                                            <th>Kontak</th>
                                            <th>Kota</th>
                                            <th>Total Pembelian</th>
                                            <th>Total Nilai</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($supplier as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $supplier->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                                            {{ strtoupper(substr($item->nama_supplier, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $item->nama_supplier }}</div>
                                                            @if($item->alamat)
                                                                <small class="fs-12 text-muted">{{ Str::limit($item->alamat, 30) }}</small>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        <i class="feather-phone fs-12 text-muted me-1"></i>
                                                        <span>{{ $item->no_telp }}</span>
                                                    </div>
                                                    @if($item->email)
                                                        <div>
                                                            <i class="feather-mail fs-12 text-muted me-1"></i>
                                                            <span class="fs-12">{{ $item->email }}</span>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($item->kota)
                                                        <span class="badge bg-soft-secondary text-secondary">
                                                            <i class="feather-map-pin fs-10 me-1"></i>{{ $item->kota }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted fs-12">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item->pembelian_count ?? 0 }} transaksi
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-success">
                                                        Rp {{ number_format($item->pembelian_sum_total_harga ?? 0, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->pembelian_count > 0)
                                                        <span class="badge bg-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-secondary">Belum Transaksi</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('supplier.show', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('supplier.edit', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('supplier.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="avatar-text avatar-md text-danger" data-bs-toggle="tooltip" title="Hapus" style="border: none; background: none;">
                                                                <i class="feather-trash-2"></i>
                                                            </button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-package fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data supplier</p>
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
                                        Menampilkan {{ $supplier->firstItem() ?? 0 }} sampai {{ $supplier->lastItem() ?? 0 }} dari {{ $supplier->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($supplier->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $supplier->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $supplier->lastPage()) as $page)
                                            @if ($page == $supplier->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $supplier->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($supplier->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $supplier->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
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
/* Avatar Text */
.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}

/* Custom Checkbox Fix */
.custom-control-label::before,
.custom-control-label::after {
    top: 0.15rem;
}

/* Badge Styling */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
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
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('#supplierTable tbody tr').filter(function() {
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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} data supplier terpilih?`)) {
            $.ajax({
                url: '{{ route("supplier.destroyAll") }}',
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
        
        if (confirm('Yakin ingin menghapus data supplier ini?')) {
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
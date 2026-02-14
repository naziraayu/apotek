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
                    <h5 class="m-b-10">Data Pelanggan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Pelanggan</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Pelanggan</span>
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
                            <h5 class="card-title">Daftar Pelanggan</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('pelanggan.index') }}" class="avatar-text avatar-xs bg-warning" data-bs-toggle="tooltip" title="Refresh">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('pelanggan.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Status Pelanggan -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Pelanggan</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="vip" {{ request('status') == 'vip' ? 'selected' : '' }}>VIP (>5 Juta)</option>
                                                <option value="loyal" {{ request('status') == 'loyal' ? 'selected' : '' }}>Loyal (>5 Transaksi)</option>
                                                <option value="regular" {{ request('status') == 'regular' ? 'selected' : '' }}>Regular</option>
                                            </select>
                                        </div>

                                        <!-- Aktivitas -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Aktivitas</label>
                                            <select name="aktivitas" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                <option value="aktif" {{ request('aktivitas') == 'aktif' ? 'selected' : '' }}>Aktif (30 Hari)</option>
                                                <option value="tidak_aktif" {{ request('aktivitas') == 'tidak_aktif' ? 'selected' : '' }}>Tidak Aktif</option>
                                                <option value="belum_transaksi" {{ request('aktivitas') == 'belum_transaksi' ? 'selected' : '' }}>Belum Transaksi</option>
                                            </select>
                                        </div>

                                        <!-- Periode Pendaftaran -->
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
                                                <a href="{{ route('pelanggan.index') }}" class="btn btn-sm btn-light">
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
                                            <input type="text" class="form-control" id="searchInput" placeholder="Cari nama, telepon, email...">
                                            <button class="btn btn-primary" type="button">
                                                <i class="feather-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="pelangganTable">
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
                                            <th>Nama Pelanggan</th>
                                            <th>Kontak</th>
                                            <th>Tanggal Daftar</th>
                                            <th>Total Transaksi</th>
                                            <th>Total Belanja</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pelanggan as $index => $item)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $item->id }}" value="{{ $item->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $item->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $pelanggan->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                                            {{ strtoupper(substr($item->nama_pelanggan, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $item->nama_pelanggan }}</div>
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
                                                    <div>{{ $item->tanggal_daftar->format('d/m/Y') }}</div>
                                                    <small class="fs-12 text-muted">{{ $item->lama_pelanggan }} hari</small>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item->penjualan_count ?? 0 }} transaksi
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="fw-bold text-success">
                                                        Rp {{ number_format($item->penjualan_sum_grand_total ?? 0, 0, ',', '.') }}
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($item->isVip())
                                                        <span class="badge bg-warning text-dark">VIP</span>
                                                    @elseif($item->isLoyal())
                                                        <span class="badge bg-info">Loyal</span>
                                                    @else
                                                        <span class="badge bg-secondary">Regular</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('pelanggan.show', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('pelanggan.edit', $item->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('pelanggan.destroy', $item->id) }}" method="POST" class="d-inline delete-form">
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
                                                        <i class="feather-users fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data pelanggan</p>
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
                                        Menampilkan {{ $pelanggan->firstItem() ?? 0 }} sampai {{ $pelanggan->lastItem() ?? 0 }} dari {{ $pelanggan->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($pelanggan->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $pelanggan->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $pelanggan->lastPage()) as $page)
                                            @if ($page == $pelanggan->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $pelanggan->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($pelanggan->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $pelanggan->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
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
        $('#pelangganTable tbody tr').filter(function() {
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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} data pelanggan terpilih?`)) {
            $.ajax({
                url: '{{ route("pelanggan.destroyAll") }}',
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
        
        if (confirm('Yakin ingin menghapus data pelanggan ini?')) {
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
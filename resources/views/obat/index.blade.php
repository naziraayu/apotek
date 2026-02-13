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
                    <h5 class="m-b-10">Master Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Obat</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
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
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Obat</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('obat.index') }}" class="avatar-text avatar-xs bg-warning" data-bs-toggle="tooltip" title="Refresh">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section - IMPROVED VERSION -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('obat.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Kategori Filter -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Kategori</label>
                                            <select name="kategori_id" class="form-select form-select-sm">
                                                <option value="">Semua Kategori</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Status Stok Filter -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Stok</label>
                                            <select name="status_stok" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                <option value="minimum" {{ request('status_stok') == 'minimum' ? 'selected' : '' }}>Stok Minimum</option>
                                                <option value="habis" {{ request('status_stok') == 'habis' ? 'selected' : '' }}>Stok Habis</option>
                                            </select>
                                        </div>

                                        <!-- Kadaluarsa Checkbox -->
                                        <div class="col-md-2 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">&nbsp;</label>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="kadaluarsa" value="1" id="kadaluarsa" {{ request('kadaluarsa') ? 'checked' : '' }}>
                                                <label class="form-check-label fs-13" for="kadaluarsa">
                                                    Akan Kadaluarsa
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-5 col-sm-6">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('obat.index') }}" class="btn btn-sm btn-light">
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

                            <!-- Bulk Actions -->
                            <div class="px-4 pt-3">
                                <button type="button" class="btn btn-danger btn-sm" id="deleteSelected" style="display: none;">
                                    <i class="feather-trash-2 me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                                </button>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="obatTable">
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
                                            <th>Nama Obat</th>
                                            <th>Kategori</th>
                                            <th>Satuan</th>
                                            <th>Harga Jual</th>
                                            <th>Stok</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obats as $index => $obat)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $obat->id }}" value="{{ $obat->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $obat->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $obats->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $obat->nama_obat }}</div>
                                                    @if($obat->no_batch)
                                                        <small class="fs-12 text-muted">Batch: {{ $obat->no_batch }}</small>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary">
                                                        {{ $obat->kategori->nama_kategori ?? '-' }}
                                                    </span>
                                                </td>
                                                <td>{{ $obat->satuan }}</td>
                                                <td>{{ $obat->harga_jual_format }}</td>
                                                <td>
                                                    <span class="badge {{ $obat->stok == 0 ? 'bg-soft-danger text-danger' : ($obat->isStokMinimum() ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success') }}">
                                                        {{ $obat->stok }} {{ $obat->satuan }}
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($obat->stok == 0)
                                                        <span class="badge bg-soft-danger text-danger">Habis</span>
                                                    @elseif($obat->isStokMinimum())
                                                        <span class="badge bg-soft-warning text-warning">Stok Minimum</span>
                                                    @elseif($obat->isNearExpired())
                                                        <span class="badge bg-soft-info text-info">Akan Kadaluarsa</span>
                                                    @else
                                                        <span class="badge bg-soft-success text-success">Normal</span>
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
                                                        <form action="{{ route('obat.destroy', $obat->id) }}" method="POST" class="d-inline delete-form">
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
                                                        <i class="feather-inbox fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data obat</p>
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
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($obats->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $obats->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $obats->lastPage()) as $page)
                                            @if ($page == $obats->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $obats->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($obats->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $obats->nextPageUrl() }}" rel="next">›</a>
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
/* Fix Pagination Size */
.pagination-wrapper .pagination {
    margin: 0;
    gap: 5px;
}

.pagination-wrapper .page-link {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
    border-radius: 4px;
    border: 1px solid #dee2e6;
    color: #6c757d;
}

.pagination-wrapper .page-item.active .page-link {
    background-color: #6777ef;
    border-color: #6777ef;
    color: #fff;
}

.pagination-wrapper .page-link:hover {
    color: #6777ef;
    background-color: #f8f9fa;
}

.pagination-wrapper .page-item.disabled .page-link {
    opacity: 0.5;
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
    // Initialize DataTable
    var table = $('#obatTable').DataTable({
        "pageLength": 10,
        "lengthMenu": [[10, 25, 50, 100, -1], [10, 25, 50, 100, "Semua"]],
        "language": {
            "search": "Cari:",
            "lengthMenu": "Tampilkan _MENU_ data",
            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
            "infoFiltered": "(difilter dari _MAX_ total data)",
            "zeroRecords": "Tidak ada data yang ditemukan",
            "emptyTable": "Tidak ada data tersedia",
            "paginate": {
                "first": "Pertama",
                "last": "Terakhir",
                "next": "Selanjutnya",
                "previous": "Sebelumnya"
            }
        },
        "ordering": true,
        "searching": true,
        "paging": false, // Karena sudah pakai Laravel pagination
        "info": false, // Karena sudah ada custom info
        "columnDefs": [
            { "orderable": false, "targets": [0, 8] }, // Checkbox dan action tidak bisa diurutkan
            { "searchable": false, "targets": [0, 8] }
        ],
        "order": [[1, 'asc']] // Urutkan berdasarkan nomor
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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} data terpilih?`)) {
            $.ajax({
                url: '{{ route("obat.destroyAll") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Gagal menghapus data');
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
        
        if (confirm('Yakin ingin menghapus data ini?')) {
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
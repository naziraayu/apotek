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
                    <h5 class="m-b-10">Kategori Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Master Data</li>
                    <li class="breadcrumb-item active">Kategori Obat</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('kategori-obat.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Kategori</span>
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
                            <h5 class="card-title">Daftar Kategori Obat</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('kategori-obat.index') }}" class="avatar-text avatar-xs bg-warning" data-bs-toggle="tooltip" title="Refresh">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('kategori-obat.index') }}" id="filterForm">
                                    <div class="row g-2 align-items-end">
                                        <!-- Status Filter -->
                                        <div class="col-md-3 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status</label>
                                            <select name="status" class="form-select form-select-sm">
                                                <option value="">Semua Status</option>
                                                <option value="has_obat" {{ request('status') == 'has_obat' ? 'selected' : '' }}>Memiliki Obat</option>
                                                <option value="empty" {{ request('status') == 'empty' ? 'selected' : '' }}>Kosong</option>
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div class="col-md-4 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Pencarian</label>
                                            <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama kategori..." value="{{ request('search') }}">
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-5 col-sm-12">
                                            <div class="d-flex gap-2 justify-content-end">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('kategori-obat.index') }}" class="btn btn-sm btn-light">
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
                                <table class="table table-hover" id="kategoriTable">
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
                                            <th>Nama Kategori</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah Obat</th>
                                            <th>Status</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($kategoris as $index => $kategori)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" id="customCheck{{ $kategori->id }}" value="{{ $kategori->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $kategori->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $kategoris->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $kategori->nama_kategori }}</div>
                                                </td>
                                                <td>
                                                    <span class="text-muted fs-12">
                                                        {{ $kategori->deskripsi ? Str::limit($kategori->deskripsi, 50) : '-' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $kategori->obat_count > 0 ? 'bg-soft-primary text-primary' : 'bg-soft-secondary text-secondary' }}">
                                                        {{ $kategori->obat_count }} Obat
                                                    </span>
                                                </td>
                                                <td>
                                                    @if($kategori->obat_count > 0)
                                                        <span class="badge bg-soft-success text-success">Aktif</span>
                                                    @else
                                                        <span class="badge bg-soft-secondary text-secondary">Kosong</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('kategori-obat.show', $kategori->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('kategori-obat.edit', $kategori->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        <form action="{{ route('kategori-obat.destroy', $kategori->id) }}" method="POST" class="d-inline delete-form">
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
                                                <td colspan="7" class="text-center">
                                                    <div class="py-5">
                                                        <i class="feather-inbox fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data kategori obat</p>
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
                                        Menampilkan {{ $kategoris->firstItem() ?? 0 }} sampai {{ $kategoris->lastItem() ?? 0 }} dari {{ $kategoris->total() }} data
                                    </span>
                                </div>
                                <nav aria-label="Pagination">
                                    <ul class="pagination pagination-sm mb-0">
                                        {{-- Previous --}}
                                        @if ($kategoris->onFirstPage())
                                            <li class="page-item disabled">
                                                <span class="page-link">‹</span>
                                            </li>
                                        @else
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $kategoris->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                            </li>
                                        @endif

                                        {{-- Pagination Elements --}}
                                        @foreach(range(1, $kategoris->lastPage()) as $page)
                                            @if ($page == $kategoris->currentPage())
                                                <li class="page-item active">
                                                    <span class="page-link">{{ $page }}</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $kategoris->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                </li>
                                            @endif
                                        @endforeach

                                        {{-- Next --}}
                                        @if ($kategoris->hasMorePages())
                                            <li class="page-item">
                                                <a class="page-link" href="{{ $kategoris->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} kategori terpilih?`)) {
            $.ajax({
                url: '{{ route("kategori-obat.destroyAll") }}',
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
                error: function(xhr) {
                    var message = xhr.responseJSON?.message || 'Terjadi kesalahan';
                    alert(message);
                }
            });
        }
    });

    // Delete Single Item with Confirmation
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        
        if (confirm('Yakin ingin menghapus kategori ini?')) {
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
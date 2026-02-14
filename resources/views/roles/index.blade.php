@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Manajemen Role</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Pengaturan</li>
                    <li class="breadcrumb-item active">Role</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('roles.create') }}" class="btn btn-primary">
                            <i class="feather-plus me-2"></i>
                            <span>Tambah Role</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Role & Hak Akses</h5>
                            <div class="card-header-action">
                                <div class="card-header-btn">
                                    <div data-bs-toggle="tooltip" title="Refresh">
                                        <a href="{{ route('roles.index') }}" class="avatar-text avatar-xs bg-warning">
                                            <i class="feather-refresh-cw"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <!-- Filter Section -->
                            <div class="p-3 border-bottom bg-light">
                                <form method="GET" action="{{ route('roles.index') }}">
                                    <div class="row g-2 align-items-end">
                                        <!-- User Status Filter -->
                                        <div class="col-md-4 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Status Penggunaan</label>
                                            <select name="user_status" class="form-select form-select-sm">
                                                <option value="">Semua</option>
                                                <option value="has_users" {{ request('user_status') == 'has_users' ? 'selected' : '' }}>
                                                    Sedang Digunakan
                                                </option>
                                                <option value="no_users" {{ request('user_status') == 'no_users' ? 'selected' : '' }}>
                                                    Tidak Digunakan
                                                </option>
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div class="col-md-6 col-sm-6">
                                            <label class="form-label text-muted fs-12 mb-1">Cari</label>
                                            <input type="text" name="search" class="form-control form-control-sm" 
                                                   value="{{ request('search') }}" placeholder="Cari nama role...">
                                        </div>

                                        <!-- Action Buttons -->
                                        <div class="col-md-2 col-sm-6">
                                            <div class="d-flex flex-column gap-2">
                                                <button type="submit" class="btn btn-sm btn-primary">
                                                    <i class="feather-search me-1"></i>Filter
                                                </button>
                                                <a href="{{ route('roles.index') }}" class="btn btn-sm btn-light">
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

                            <!-- Action Bar -->
                            <div class="px-4 pt-3">
                                <div class="row">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-danger btn-sm" id="deleteSelected" style="display: none;">
                                            <i class="feather-trash-2 me-2"></i>Hapus Terpilih (<span id="selectedCount">0</span>)
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Table -->
                            <div class="table-responsive">
                                <table class="table table-hover" id="roleTable">
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
                                            <th>Nama Role</th>
                                            <th>Deskripsi</th>
                                            <th>Jumlah User</th>
                                            <th>Total Permission</th>
                                            <th class="text-end">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($roles as $index => $role)
                                            <tr>
                                                <td>
                                                    <div class="custom-control custom-checkbox ms-1">
                                                        <input type="checkbox" class="custom-control-input row-checkbox" 
                                                               id="customCheck{{ $role->id }}" value="{{ $role->id }}">
                                                        <label class="custom-control-label" for="customCheck{{ $role->id }}"></label>
                                                    </div>
                                                </td>
                                                <td>{{ $roles->firstItem() + $index }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                                            {{ strtoupper(substr($role->nama_role, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold text-dark">{{ $role->nama_role }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if($role->deskripsi)
                                                        <span class="text-muted">{{ Str::limit($role->deskripsi, 50) }}</span>
                                                    @else
                                                        <span class="text-muted fst-italic">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        <i class="feather-users me-1"></i>
                                                        {{ $role->users_count }} user
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-success text-success">
                                                        <i class="feather-shield me-1"></i>
                                                        {{ $role->permissions_count }} permission
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="hstack gap-2 justify-content-end">
                                                        <a href="{{ route('roles.show', $role->id) }}" 
                                                           class="avatar-text avatar-md" 
                                                           data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                        <a href="{{ route('roles.edit', $role->id) }}" 
                                                           class="avatar-text avatar-md" 
                                                           data-bs-toggle="tooltip" title="Edit">
                                                            <i class="feather-edit"></i>
                                                        </a>
                                                        {{-- <a href="{{ route('roles.clone', $role->id) }}" 
                                                           class="avatar-text avatar-md text-warning" 
                                                           data-bs-toggle="tooltip" title="Duplikat">
                                                            <i class="feather-copy"></i>
                                                        </a> --}}
                                                        <form action="{{ route('roles.destroy', $role->id) }}" 
                                                              method="POST" class="d-inline delete-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" 
                                                                    class="avatar-text avatar-md text-danger" 
                                                                    data-bs-toggle="tooltip" title="Hapus" 
                                                                    style="border: none; background: none;">
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
                                                        <i class="feather-shield fs-1 text-muted"></i>
                                                        <p class="mt-3 text-muted">Tidak ada data role</p>
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
                                        Menampilkan {{ $roles->firstItem() ?? 0 }} sampai {{ $roles->lastItem() ?? 0 }} dari {{ $roles->total() }} data
                                    </span>
                                </div>
                                <nav>
                                    {{ $roles->appends(request()->query())->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Check All
    $('#checkAll').on('change', function() {
        $('.row-checkbox').prop('checked', this.checked);
        toggleDeleteButton();
    });

    $(document).on('change', '.row-checkbox', function() {
        toggleDeleteButton();
    });

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
        
        if (confirm(`Yakin ingin menghapus ${ids.length} role terpilih?`)) {
            $.ajax({
                url: '{{ route("roles.destroyAll") }}',
                method: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                }
            });
        }
    });

    // Delete Form
    $(document).on('submit', '.delete-form', function(e) {
        e.preventDefault();
        if (confirm('Yakin ingin menghapus role ini?')) {
            this.submit();
        }
    });

    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
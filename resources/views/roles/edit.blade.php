@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Role</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                    <li class="breadcrumb-item active">Edit</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('roles.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <form action="{{ route('roles.update', $role->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <!-- Role Info -->
                    <div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Role</h5>
                            </div>
                            <div class="card-body">
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <!-- Role Info Header -->
                                <div class="mb-4 p-3 bg-light rounded">
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-text avatar-lg bg-soft-primary text-primary me-3">
                                            {{ strtoupper(substr($role->nama_role, 0, 2)) }}
                                        </div>
                                        <div>
                                            <h6 class="mb-1">{{ $role->nama_role }}</h6>
                                            <small class="text-muted">
                                                <i class="feather-users me-1"></i>
                                                {{ $role->users()->count() }} user menggunakan role ini
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Nama Role -->
                                <div class="mb-4">
                                    <label class="form-label">Nama Role <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_role" 
                                           class="form-control @error('nama_role') is-invalid @enderror" 
                                           value="{{ old('nama_role', $role->nama_role) }}" 
                                           placeholder="Contoh: Manager, Kasir, Admin">
                                    @error('nama_role')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-4">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea name="deskripsi" rows="4" 
                                              class="form-control @error('deskripsi') is-invalid @enderror" 
                                              placeholder="Jelaskan tugas dan tanggung jawab role ini...">{{ old('deskripsi', $role->deskripsi) }}</textarea>
                                    @error('deskripsi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Quick Actions -->
                                <div class="d-grid gap-2">
                                    <button type="button" class="btn btn-sm btn-light" id="selectAll">
                                        <i class="feather-check-square me-2"></i>Pilih Semua Permission
                                    </button>
                                    <button type="button" class="btn btn-sm btn-light" id="deselectAll">
                                        <i class="feather-square me-2"></i>Hapus Semua Pilihan
                                    </button>
                                </div>

                                <!-- Stats -->
                                <div class="mt-4 pt-3 border-top">
                                    <small class="text-muted d-block mb-1">Permission yang dipilih:</small>
                                    <h4 class="mb-0" id="selectedCount">{{ count($rolePermissions) }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Permissions -->
                    <div class="col-lg-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Hak Akses (Permissions)</h5>
                                <small class="text-muted">Pilih akses yang diizinkan untuk role ini</small>
                            </div>
                            <div class="card-body">
                                @if($groupedPermissions->isEmpty())
                                    <div class="alert alert-warning">
                                        <i class="feather-alert-triangle me-2"></i>
                                        Belum ada permission tersedia.
                                    </div>
                                @else
                                    @foreach($groupedPermissions as $feature => $permissions)
                                        <div class="mb-4 pb-3 border-bottom">
                                            <div class="d-flex align-items-center mb-3">
                                                <div class="avatar-text avatar-sm bg-soft-primary text-primary me-2">
                                                    <i class="feather-shield"></i>
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $feature) }}</h6>
                                                    <small class="text-muted">Module {{ $feature }}</small>
                                                </div>
                                                <div class="ms-auto">
                                                    <button type="button" class="btn btn-sm btn-soft-primary select-feature" 
                                                            data-feature="{{ $feature }}">
                                                        <i class="feather-check me-1"></i>Pilih Semua
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="row g-2">
                                                @foreach($permissions as $permission)
                                                    <div class="col-md-3 col-sm-6">
                                                        <div class="form-check form-check-sm">
                                                            <input class="form-check-input permission-checkbox" 
                                                                   type="checkbox" 
                                                                   name="permissions[]" 
                                                                   value="{{ $permission->id }}"
                                                                   id="permission{{ $permission->id }}"
                                                                   data-feature="{{ $feature }}"
                                                                   {{ in_array($permission->id, old('permissions', $rolePermissions)) ? 'checked' : '' }}>
                                                            <label class="form-check-label text-capitalize" 
                                                                   for="permission{{ $permission->id }}">
                                                                {{ $permission->action }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                @endif

                                @error('permissions')
                                    <div class="alert alert-danger mt-3">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="card-footer">
                                <div class="d-flex gap-2 justify-content-end">
                                    <a href="{{ route('roles.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Update Role
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
.form-check-sm .form-check-input {
    margin-top: 0.15rem;
}

.form-check-sm .form-check-label {
    font-size: 0.875rem;
}

.permission-checkbox:checked + label {
    font-weight: 600;
    color: var(--bs-primary);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Update counter
    function updateCounter() {
        var count = $('.permission-checkbox:checked').length;
        $('#selectedCount').text(count);
    }

    // Select All Permissions
    $('#selectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', true);
        updateCounter();
    });

    // Deselect All Permissions
    $('#deselectAll').on('click', function() {
        $('.permission-checkbox').prop('checked', false);
        updateCounter();
    });

    // Select all permissions in a feature
    $(document).on('click', '.select-feature', function() {
        var feature = $(this).data('feature');
        $('.permission-checkbox[data-feature="' + feature + '"]').prop('checked', true);
        updateCounter();
    });

    // Update counter on change
    $(document).on('change', '.permission-checkbox', function() {
        updateCounter();
    });

    // Initial counter
    updateCounter();
});
</script>
@endpush
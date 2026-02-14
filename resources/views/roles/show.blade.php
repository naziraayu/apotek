@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Detail Role</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Role</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i>
                            <span>Edit Role</span>
                        </a>
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
            <div class="row">
                <!-- Role Info Card -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-text avatar-xxl bg-soft-primary text-primary mx-auto mb-3">
                                    {{ strtoupper(substr($role->nama_role, 0, 2)) }}
                                </div>
                                <h4 class="mb-1">{{ $role->nama_role }}</h4>
                                @if($role->deskripsi)
                                    <p class="text-muted mb-0">{{ $role->deskripsi }}</p>
                                @endif
                            </div>

                            <hr class="my-4">

                            <!-- Statistics Cards -->
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <div class="text-center p-3 bg-soft-success rounded">
                                        <i class="feather-users fs-3 text-success mb-2"></i>
                                        <h4 class="mb-0">{{ $stats['total_users'] }}</h4>
                                        <small class="text-muted">Total User</small>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center p-3 bg-soft-primary rounded">
                                        <i class="feather-shield fs-3 text-primary mb-2"></i>
                                        <h4 class="mb-0">{{ $stats['total_permissions'] }}</h4>
                                        <small class="text-muted">Permissions</small>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <!-- Additional Info -->
                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">User Aktif</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-check-circle text-success me-2"></i>
                                    <span>{{ $stats['active_users'] }} user aktif</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Dibuat</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-calendar text-primary me-2"></i>
                                    <span>{{ $role->created_at->format('d F Y') }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Terakhir Diupdate</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-clock text-primary me-2"></i>
                                    <span>{{ $role->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Permissions & Users -->
                <div class="col-lg-8">
                    <!-- Permissions Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Hak Akses (Permissions)</h5>
                        </div>
                        <div class="card-body">
                            @if($groupedPermissions->isEmpty())
                                <div class="text-center py-5">
                                    <i class="feather-alert-circle fs-1 text-muted mb-3"></i>
                                    <p class="text-muted">Role ini belum memiliki permission</p>
                                    <a href="{{ route('roles.edit', $role->id) }}" class="btn btn-sm btn-primary">
                                        <i class="feather-plus me-2"></i>Tambah Permission
                                    </a>
                                </div>
                            @else
                                @foreach($groupedPermissions as $feature => $permissions)
                                    <div class="mb-4 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-text avatar-sm bg-soft-primary text-primary me-2">
                                                <i class="feather-shield"></i>
                                            </div>
                                            <div>
                                                <h6 class="mb-0 text-capitalize">{{ str_replace('_', ' ', $feature) }}</h6>
                                                <small class="text-muted">{{ $permissions->count() }} permissions</small>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($permissions as $permission)
                                                <span class="badge bg-soft-primary text-primary">
                                                    <i class="feather-check-circle me-1"></i>
                                                    {{ $permission->action }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <!-- Users List -->
                    @if($role->users->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">User dengan Role Ini</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>User</th>
                                                <th>Email</th>
                                                <th>Status</th>
                                                <th>Bergabung</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($role->users->take(10) as $user)
                                                <tr>
                                                    <td>
                                                        <div class="d-flex align-items-center">
                                                            <div class="avatar-text avatar-sm bg-soft-primary text-primary me-2">
                                                                {{ strtoupper(substr($user->name, 0, 2)) }}
                                                            </div>
                                                            <a href="{{ route('users.show', $user->id) }}" class="text-dark">
                                                                {{ $user->name }}
                                                            </a>
                                                        </div>
                                                    </td>
                                                    <td>{{ $user->email }}</td>
                                                    <td>
                                                        @if($user->status === 'aktif')
                                                            <span class="badge bg-success">Aktif</span>
                                                        @else
                                                            <span class="badge bg-danger">Non-Aktif</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($role->users->count() > 10)
                                <div class="card-footer text-center">
                                    <a href="{{ route('users.index', ['role_id' => $role->id]) }}" class="btn btn-sm btn-light">
                                        Lihat Semua User ({{ $role->users->count() }})
                                    </a>
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="feather-users fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Belum ada user yang menggunakan role ini</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

@push('styles')
<style>
.avatar-xxl {
    width: 120px;
    height: 120px;
    font-size: 3rem;
    border-radius: 0.5rem;
}
</style>
@endpush
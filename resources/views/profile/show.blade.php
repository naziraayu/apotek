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
                    <h5 class="m-b-10">Profile Details</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Profile Details</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex d-md-none">
                        <a href="javascript:void(0)" class="page-header-right-close-toggle">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Back</span>
                        </a>
                    </div>
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i>
                            <span>Edit Profile</span>
                        </a>
                    </div>
                </div>
                <div class="d-md-none d-flex align-items-center">
                    <a href="javascript:void(0)" class="page-header-right-open-toggle">
                        <i class="feather-align-right fs-20"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <!-- Profile Card -->
                <div class="col-xxl-4 col-xl-5">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="mx-auto mb-4" style="width: 150px; height: 150px;">
                                    @if(Auth::user()->photo)
                                        <img src="{{ asset('storage/' . Auth::user()->photo) }}" alt="Profile" class="img-fluid rounded-circle border border-3" style="width: 100%; height: 100%; object-fit: cover;">
                                    @else
                                        <div class="avatar-text avatar-xxl bg-soft-primary text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 100%; height: 100%; font-size: 3rem;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                </div>
                                <h5 class="mb-1">{{ Auth::user()->name }}</h5>
                                @if(Auth::user()->role)
                                    <span class="badge bg-soft-success text-success">{{ Auth::user()->role->nama_role }}</span>
                                @endif
                                <p class="text-muted mt-2">{{ Auth::user()->email }}</p>
                            </div>

                            <hr class="my-4">

                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Account Information</h6>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted">Member Since</span>
                                    <span class="fw-semibold">{{ Auth::user()->created_at->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted">Last Updated</span>
                                    <span class="fw-semibold">{{ Auth::user()->updated_at->format('d M Y') }}</span>
                                </div>
                                <div class="d-flex align-items-center justify-content-between">
                                    <span class="text-muted">Account Status</span>
                                    <span class="badge bg-soft-success text-success">Active</span>
                                </div>
                            </div>

                            @if(Auth::user()->role)
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3">Role Information</h6>
                                <div class="d-flex align-items-center justify-content-between mb-3">
                                    <span class="text-muted">Role Name</span>
                                    <span class="fw-semibold">{{ Auth::user()->role->nama_role }}</span>
                                </div>
                                @if(Auth::user()->role->deskripsi)
                                <div class="mt-2">
                                    <span class="text-muted d-block mb-1">Description</span>
                                    <p class="text-muted small mb-0">{{ Auth::user()->role->deskripsi }}</p>
                                </div>
                                @endif
                            </div>
                            @endif

                            <div class="d-grid gap-2">
                                <a href="{{ route('profile.edit') }}" class="btn btn-primary">
                                    <i class="feather-edit me-2"></i>Edit Profile
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Profile Details -->
                <div class="col-xxl-8 col-xl-7">
                    <!-- Personal Information -->
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Personal Information</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Full Name</label>
                                        <p class="fw-semibold mb-0">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Address</label>
                                        <p class="fw-semibold mb-0">{{ Auth::user()->email }}</p>
                                    </div>
                                </div>
                                @if(Auth::user()->role)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Role</label>
                                        <p class="fw-semibold mb-0">
                                            <span class="badge bg-soft-success text-success">{{ Auth::user()->role->nama_role }}</span>
                                        </p>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Account Status</label>
                                        <p class="fw-semibold mb-0">
                                            <span class="badge bg-soft-success text-success">
                                                <i class="feather-check-circle me-1"></i>Active
                                            </span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Activity -->
                    <div class="card stretch stretch-full mb-4">
                        <div class="card-header">
                            <h5 class="card-title">Account Activity</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Account Created</label>
                                        <p class="fw-semibold mb-0">{{ Auth::user()->created_at->format('d F Y, H:i') }}</p>
                                        <small class="text-muted">{{ Auth::user()->created_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Last Updated</label>
                                        <p class="fw-semibold mb-0">{{ Auth::user()->updated_at->format('d F Y, H:i') }}</p>
                                        <small class="text-muted">{{ Auth::user()->updated_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @if(Auth::user()->email_verified_at)
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label class="form-label text-muted">Email Verified</label>
                                        <p class="fw-semibold mb-0">{{ Auth::user()->email_verified_at->format('d F Y, H:i') }}</p>
                                        <small class="text-muted">{{ Auth::user()->email_verified_at->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if(Auth::user()->role && Auth::user()->role->permissions->count() > 0)
                    <!-- Permissions -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Permissions & Access</h5>
                        </div>
                        <div class="card-body">
                            <p class="text-muted mb-3">Your role has access to the following modules and actions:</p>
                            <div class="row g-3">
                                @php
                                    $groupedPermissions = Auth::user()->role->permissions->groupBy('modul');
                                @endphp
                                @foreach($groupedPermissions as $modul => $permissions)
                                <div class="col-md-6">
                                    <div class="border rounded p-3 h-100">
                                        <h6 class="fw-bold text-primary mb-2">
                                            <i class="feather-shield me-2"></i>{{ ucwords(str_replace('_', ' ', $modul)) }}
                                        </h6>
                                        <div class="d-flex flex-wrap gap-1">
                                            @foreach($permissions as $permission)
                                                <span class="badge bg-soft-primary text-primary">{{ $permission->aksi }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>

    <!-- [ Footer ] start -->
    <footer class="footer">
        <p class="fs-11 text-muted fw-medium text-uppercase mb-0 copyright">
            <span>Copyright ©</span>
            <script>
                document.write(new Date().getFullYear());
            </script>
        </p>
        <div class="d-flex align-items-center gap-4">
            <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Help</a>
            <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Terms</a>
            <a href="javascript:void(0);" class="fs-11 fw-semibold text-uppercase">Privacy</a>
        </div>
    </footer>
    <!-- [ Footer ] end -->
</main>
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->
@endsection
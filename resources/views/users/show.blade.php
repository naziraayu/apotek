@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Detail User</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('users.index') }}">User</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('users.edit', $user->id) }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i>
                            <span>Edit User</span>
                        </a>
                        <a href="{{ route('users.index') }}" class="btn btn-light">
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
                <!-- User Profile Card -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="text-center">
                                <div class="avatar-text avatar-xxl bg-soft-primary text-primary mx-auto mb-3">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                                <h5 class="mb-1">{{ $user->name }}</h5>
                                <p class="text-muted mb-3">{{ $user->role_name }}</p>
                                
                                @if($user->status === 'aktif')
                                    <span class="badge bg-success mb-3">Aktif</span>
                                @else
                                    <span class="badge bg-danger mb-3">Non-Aktif</span>
                                @endif
                            </div>

                            <hr class="my-4">

                            <!-- Contact Info -->
                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Email</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-mail text-primary me-2"></i>
                                    <span>{{ $user->email }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Telepon</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-phone text-primary me-2"></i>
                                    <span>{{ $user->no_telp }}</span>
                                </div>
                            </div>

                            @if($user->alamat)
                                <div class="mb-3">
                                    <label class="text-muted fs-12 mb-1">Alamat</label>
                                    <div class="d-flex align-items-start">
                                        <i class="feather-map-pin text-primary me-2 mt-1"></i>
                                        <span>{{ $user->alamat }}</span>
                                    </div>
                                </div>
                            @endif

                            <hr class="my-4">

                            <!-- Additional Info -->
                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Bergabung</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-calendar text-primary me-2"></i>
                                    <span>{{ $stats['bergabung'] }}</span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="text-muted fs-12 mb-1">Terdaftar</label>
                                <div class="d-flex align-items-center">
                                    <i class="feather-clock text-primary me-2"></i>
                                    <span>{{ $user->created_at->format('d F Y, H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistics & Activity -->
                <div class="col-lg-8">
                    <!-- Statistics Cards -->
                    <div class="row">
                        <div class="col-md-6 mb-4">
                            <div class="card stretch stretch-full">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1">Total Penjualan</p>
                                            <h3 class="mb-0">{{ $stats['total_penjualan'] }}</h3>
                                            <small class="text-success">
                                                Rp {{ number_format($stats['nilai_penjualan'], 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-success text-success">
                                            <i class="feather-shopping-cart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card stretch stretch-full">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1">Total Pembelian</p>
                                            <h3 class="mb-0">{{ $stats['total_pembelian'] }}</h3>
                                            <small class="text-info">
                                                Rp {{ number_format($stats['nilai_pembelian'], 0, ',', '.') }}
                                            </small>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-info text-info">
                                            <i class="feather-shopping-bag"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Penjualan -->
                    @if($user->penjualan->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Penjualan Terakhir</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>No Nota</th>
                                                <th>Tanggal</th>
                                                <th>Pelanggan</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->penjualan->take(5) as $penjualan)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('penjualan.show', $penjualan->id) }}" class="text-primary">
                                                            {{ $penjualan->no_nota }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                                                    <td>{{ $penjualan->pelanggan_nama }}</td>
                                                    <td class="text-end">
                                                        <span class="fw-bold text-success">
                                                            {{ $penjualan->grand_total_format }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($user->penjualan->count() > 5)
                                <div class="card-footer text-center">
                                    <a href="{{ route('penjualan.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-light">
                                        Lihat Semua Penjualan
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- Recent Pembelian -->
                    @if($user->pembelian->count() > 0)
                        <div class="card">
                            <div class="card-header">
                                <h6 class="card-title mb-0">Pembelian Terakhir</h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>No Nota</th>
                                                <th>Tanggal</th>
                                                <th>Supplier</th>
                                                <th>Status</th>
                                                <th class="text-end">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($user->pembelian->take(5) as $pembelian)
                                                <tr>
                                                    <td>
                                                        <a href="{{ route('pembelian.show', $pembelian->id) }}" class="text-primary">
                                                            {{ $pembelian->no_nota }}
                                                        </a>
                                                    </td>
                                                    <td>{{ $pembelian->tanggal_pembelian->format('d/m/Y') }}</td>
                                                    <td>{{ $pembelian->supplier_nama }}</td>
                                                    <td>{!! $pembelian->status_badge !!}</td>
                                                    <td class="text-end">
                                                        <span class="fw-bold text-info">
                                                            {{ $pembelian->total_harga_format }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            @if($user->pembelian->count() > 5)
                                <div class="card-footer text-center">
                                    <a href="{{ route('pembelian.index', ['user_id' => $user->id]) }}" class="btn btn-sm btn-light">
                                        Lihat Semua Pembelian
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endif

                    <!-- No Activity -->
                    @if($user->penjualan->count() == 0 && $user->pembelian->count() == 0)
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="feather-activity fs-1 text-muted mb-3"></i>
                                <p class="text-muted">Belum ada aktivitas transaksi</p>
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
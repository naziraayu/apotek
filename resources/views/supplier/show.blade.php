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
                    <h5 class="m-b-10">Detail Supplier</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('supplier.edit', $supplier->id) }}" class="btn btn-warning">
                            <i class="feather-edit me-2"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('supplier.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <!-- Informasi Supplier -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-text avatar-xl bg-soft-primary text-primary mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ strtoupper(substr($supplier->nama_supplier, 0, 2)) }}
                                </div>
                                <h4 class="fw-bold">{{ $supplier->nama_supplier }}</h4>
                                @if($supplier->pembelian_count > 0)
                                    <span class="badge bg-success">Supplier Aktif</span>
                                @else
                                    <span class="badge bg-secondary">Belum Transaksi</span>
                                @endif
                            </div>

                            <div class="border-top pt-3">
                                <h6 class="text-muted mb-3">Informasi Kontak</h6>
                                
                                <!-- Telepon -->
                                <div class="d-flex align-items-center mb-3">
                                    <div class="avatar-text avatar-sm bg-soft-info text-info me-3">
                                        <i class="feather-phone"></i>
                                    </div>
                                    <div>
                                        <small class="text-muted d-block">Telepon</small>
                                        <span class="fw-semibold">{{ $supplier->no_telp }}</span>
                                    </div>
                                </div>

                                <!-- Email -->
                                @if($supplier->email)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-text avatar-sm bg-soft-warning text-warning me-3">
                                            <i class="feather-mail"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Email</small>
                                            <span class="fw-semibold">{{ $supplier->email }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Kota -->
                                @if($supplier->kota)
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="avatar-text avatar-sm bg-soft-success text-success me-3">
                                            <i class="feather-map-pin"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Kota</small>
                                            <span class="fw-semibold">{{ $supplier->kota }}</span>
                                        </div>
                                    </div>
                                @endif

                                <!-- Alamat -->
                                @if($supplier->alamat)
                                    <div class="d-flex align-items-start mb-3">
                                        <div class="avatar-text avatar-sm bg-soft-primary text-primary me-3">
                                            <i class="feather-map"></i>
                                        </div>
                                        <div>
                                            <small class="text-muted d-block">Alamat Lengkap</small>
                                            <span class="fw-semibold">{{ $supplier->alamat }}</span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistik & Riwayat -->
                <div class="col-lg-8">
                    <!-- Statistik Cards -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="card stretch stretch-full">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1">Total Pembelian</p>
                                            <h3 class="fw-bold mb-0">{{ $supplier->pembelian_count }}</h3>
                                            <small class="text-muted">Transaksi</small>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                            <i class="feather-shopping-cart fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="card stretch stretch-full">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <p class="text-muted mb-1">Total Nilai</p>
                                            <h3 class="fw-bold mb-0 text-success">
                                                Rp {{ number_format($supplier->pembelian_sum_total_harga ?? 0, 0, ',', '.') }}
                                            </h3>
                                            <small class="text-muted">Pembelian</small>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-success text-success">
                                            <i class="feather-trending-up fs-4"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Riwayat Pembelian -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Riwayat Pembelian Terakhir</h5>
                        </div>
                        <div class="card-body p-0">
                            @if($supplier->pembelian->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>No. Pembelian</th>
                                                <th>Tanggal</th>
                                                <th>Total Item</th>
                                                <th>Grand Total</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($supplier->pembelian as $pembelian)
                                                <tr>
                                                    <td>
                                                        <span class="badge bg-soft-primary text-primary">
                                                            {{ $pembelian->no_pembelian ?? 'N/A' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div>
                                                            {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->format('d/m/Y') }}
                                                        </div>
                                                        <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($pembelian->tanggal_pembelian)->diffForHumans() }}
                                                        </small>
                                                    </td>
                                                    <td>
                                                        <span class="badge bg-soft-info text-info">
                                                            {{ $pembelian->detail_pembelian_count ?? 0 }} item
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <span class="fw-bold text-success">
                                                            Rp {{ number_format($pembelian->total_harga ?? 0, 0, ',', '.') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if($pembelian->status == 'lunas')
                                                            <span class="badge bg-success">Lunas</span>
                                                        @else
                                                            <span class="badge bg-warning">{{ ucfirst($pembelian->status) }}</span>
                                                        @endif
                                                    </td>
                                                    <td class="text-end">
                                                        <a href="{{ route('pembelian.show', $pembelian->id) }}" class="avatar-text avatar-sm" data-bs-toggle="tooltip" title="Detail">
                                                            <i class="feather-eye"></i>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="py-5 text-center">
                                    <i class="feather-shopping-cart fs-1 text-muted"></i>
                                    <p class="mt-3 text-muted">Belum ada riwayat pembelian</p>
                                </div>
                            @endif
                        </div>
                        @if($supplier->pembelian->count() > 0)
                            <div class="card-footer">
                                <a href="{{ route('pembelian.index', ['supplier' => $supplier->id]) }}" class="btn btn-sm btn-light w-100">
                                    Lihat Semua Pembelian
                                </a>
                            </div>
                        @endif
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

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
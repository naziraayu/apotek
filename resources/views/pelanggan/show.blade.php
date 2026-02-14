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
                    <h5 class="m-b-10">Detail Pelanggan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('pelanggan.edit', $pelanggan->id) }}" class="btn btn-warning">
                            <i class="feather-edit me-2"></i>
                            <span>Edit</span>
                        </a>
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-light">
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
                <!-- Info Pelanggan -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="text-center mb-4">
                                <div class="avatar-text avatar-xl bg-primary text-white mx-auto mb-3" style="width: 100px; height: 100px; font-size: 2.5rem;">
                                    {{ strtoupper(substr($pelanggan->nama_pelanggan, 0, 2)) }}
                                </div>
                                <h4 class="mb-1">{{ $pelanggan->nama_pelanggan }}</h4>
                                <div class="mb-3">
                                    {!! $pelanggan->status_pelanggan !!}
                                </div>
                                <div class="text-muted">
                                    <small>
                                        <i class="feather-calendar me-1"></i>
                                        Terdaftar sejak {{ $pelanggan->tanggal_daftar->format('d F Y') }}
                                    </small>
                                </div>
                            </div>

                            <div class="border-top pt-3">
                                <h6 class="mb-3">Informasi Kontak</h6>
                                
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">No. Telepon</small>
                                    <div class="d-flex align-items-center">
                                        <i class="feather-phone text-primary me-2"></i>
                                        <strong>{{ $pelanggan->no_telp }}</strong>
                                    </div>
                                </div>

                                @if($pelanggan->email)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Email</small>
                                    <div class="d-flex align-items-center">
                                        <i class="feather-mail text-primary me-2"></i>
                                        <strong>{{ $pelanggan->email }}</strong>
                                    </div>
                                </div>
                                @endif

                                @if($pelanggan->alamat)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Alamat</small>
                                    <div class="d-flex align-items-start">
                                        <i class="feather-map-pin text-primary me-2 mt-1"></i>
                                        <div>{{ $pelanggan->alamat }}</div>
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
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="card stretch stretch-full border-primary">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted d-block mb-1">Total Transaksi</small>
                                            <h3 class="mb-0 text-primary">{{ $statistik['total_transaksi'] }}</h3>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                            <i class="feather-shopping-cart"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card stretch stretch-full border-success">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted d-block mb-1">Total Belanja</small>
                                            <h3 class="mb-0 text-success">{{ $pelanggan->total_belanja_format }}</h3>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-success text-success">
                                            <i class="feather-dollar-sign"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="card stretch stretch-full border-info">
                                <div class="card-body">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <small class="text-muted d-block mb-1">Rata-rata Belanja</small>
                                            <h3 class="mb-0 text-info">
                                                Rp {{ number_format($statistik['rata_rata_belanja'], 0, ',', '.') }}
                                            </h3>
                                        </div>
                                        <div class="avatar-text avatar-lg bg-soft-info text-info">
                                            <i class="feather-trending-up"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Info Transaksi Terakhir -->
                    @if($statistik['transaksi_terakhir'])
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="feather-clock me-2"></i>Transaksi Terakhir
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Tanggal</small>
                                    <div class="fw-bold">
                                        {{ $statistik['transaksi_terakhir']->tanggal_penjualan->format('d F Y') }}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted d-block mb-1">Total</small>
                                    <div class="fw-bold text-success">
                                        Rp {{ number_format($statistik['transaksi_terakhir']->grand_total, 0, ',', '.') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Transaksi Per Bulan -->
                    @if($transaksiPerBulan->count() > 0)
                    <div class="card mb-4">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="feather-bar-chart-2 me-2"></i>Statistik Transaksi (6 Bulan Terakhir)
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Periode</th>
                                            <th class="text-center">Jumlah Transaksi</th>
                                            <th class="text-end">Total Belanja</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($transaksiPerBulan as $item)
                                        <tr>
                                            <td>
                                                {{ DateTime::createFromFormat('!m', $item->bulan)->format('F') }} {{ $item->tahun }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-soft-primary text-primary">
                                                    {{ $item->jumlah }} transaksi
                                                </span>
                                            </td>
                                            <td class="text-end fw-bold text-success">
                                                Rp {{ number_format($item->total, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Riwayat Transaksi -->
                    <div class="card">
                        <div class="card-header">
                            <h6 class="card-title mb-0">
                                <i class="feather-list me-2"></i>Riwayat Transaksi (10 Terakhir)
                            </h6>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>No. Invoice</th>
                                            <th>Tanggal</th>
                                            <th class="text-end">Total</th>
                                            <th class="text-center">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pelanggan->penjualan as $penjualan)
                                        <tr>
                                            <td>
                                                <span class="badge bg-soft-primary text-primary">
                                                    {{ $penjualan->no_invoice ?? '-' }}
                                                </span>
                                            </td>
                                            <td>{{ $penjualan->tanggal_penjualan->format('d/m/Y') }}</td>
                                            <td class="text-end fw-bold text-success">
                                                Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <a href="{{ route('penjualan.show', $penjualan->id) }}" 
                                                   class="btn btn-sm btn-light"
                                                   data-bs-toggle="tooltip" 
                                                   title="Lihat Detail">
                                                    <i class="feather-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4">
                                                <i class="feather-shopping-cart fs-1 text-muted"></i>
                                                <p class="mt-2 text-muted mb-0">Belum ada transaksi</p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @if($pelanggan->penjualan->count() > 0)
                        <div class="card-footer text-center">
                            <a href="{{ route('penjualan.index', ['pelanggan_id' => $pelanggan->id]) }}" class="text-primary">
                                Lihat Semua Transaksi <i class="feather-arrow-right ms-1"></i>
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

.border-primary {
    border-left: 3px solid #6777ef !important;
}

.border-success {
    border-left: 3px solid #28a745 !important;
}

.border-info {
    border-left: 3px solid #17a2b8 !important;
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
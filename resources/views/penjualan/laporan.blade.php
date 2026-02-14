@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Laporan Penjualan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-danger" onclick="exportPDF()">
                        <i class="feather-file-text me-2"></i>Export PDF
                    </button>
                    <a href="{{ route('penjualan.index') }}" class="btn btn-light">
                        <i class="feather-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Filter Laporan</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('penjualan.laporan') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Bulan</label>
                                        <select name="bulan" class="form-select">
                                            <option value="">Semua</option>
                                            @for($i = 1; $i <= 12; $i++)
                                                <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                                    {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                                </option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">Tahun</label>
                                        <select name="tahun" class="form-select">
                                            <option value="">Semua</option>
                                            @for($year = date('Y'); $year >= 2020; $year--)
                                                <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                                            @endfor
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-filter me-1"></i>Filter
                                            </button>
                                            <a href="{{ route('penjualan.laporan') }}" class="btn btn-light">
                                                <i class="feather-refresh-cw me-1"></i>Reset
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik Cards -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Transaksi</h6>
                                    <h3 class="fw-bold mb-0">{{ $totalTransaksi }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                    <i class="feather-shopping-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Penjualan</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-success text-success">
                                    <i class="feather-trending-up"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Diskon</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-warning text-warning">
                                    <i class="feather-percent"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <h6 class="text-muted mb-2">Total Profit</h6>
                                    <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($totalProfit, 0, ',', '.') }}</h3>
                                </div>
                                <div class="avatar-text avatar-lg bg-soft-info text-info">
                                    <i class="feather-dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Laporan -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Detail Laporan Penjualan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>No Nota</th>
                                            <th>Tanggal</th>
                                            <th>Pelanggan</th>
                                            <th>Kasir</th>
                                            <th>Total Item</th>
                                            <th>Total Harga</th>
                                            <th>Diskon</th>
                                            <th>Grand Total</th>
                                            <th>Profit</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($penjualans as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $item->no_nota }}</div>
                                                    <small class="text-muted">{{ $item->metode_pembayaran }}</small>
                                                </td>
                                                <td>{{ $item->tanggal_penjualan->format('d/m/Y H:i') }}</td>
                                                <td>{{ $item->pelanggan_nama }}</td>
                                                <td>{{ $item->kasir_nama }}</td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $item->total_item }} item
                                                    </span>
                                                </td>
                                                <td>{{ $item->total_harga_format }}</td>
                                                <td class="text-success">{{ $item->diskon_format }}</td>
                                                <td class="fw-bold">{{ $item->grand_total_format }}</td>
                                                <td class="text-success fw-bold">{{ $item->total_profit_format }}</td>
                                                <td>{!! $item->status_pembayaran_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="11" class="text-center py-5">
                                                    <i class="feather-inbox fs-1 text-muted"></i>
                                                    <p class="mt-3 text-muted">Tidak ada data</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($penjualans->count() > 0)
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="6" class="text-end">TOTAL:</th>
                                                <th>Rp {{ number_format($penjualans->sum('total_harga'), 0, ',', '.') }}</th>
                                                <th class="text-success">Rp {{ number_format($penjualans->sum('diskon'), 0, ',', '.') }}</th>
                                                <th>Rp {{ number_format($penjualans->sum('grand_total'), 0, ',', '.') }}</th>
                                                <th class="text-success">Rp {{ number_format($totalProfit, 0, ',', '.') }}</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</main>
@endsection

@push('scripts')
<script>
function exportPDF() {
    const params = new URLSearchParams(window.location.search);
    params.set('export', 'pdf');
    window.open(`{{ route('penjualan.laporan') }}?${params.toString()}`, '_blank');
}
</script>
@endpush
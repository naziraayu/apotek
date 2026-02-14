@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Laporan Pembelian</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                    <li class="breadcrumb-item active">Laporan</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('pembelian.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <!-- Filter & Export -->
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Filter Laporan</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('pembelian.laporan') }}" id="filterForm">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Mulai</label>
                                        <input type="date" name="tanggal_mulai" class="form-control" value="{{ request('tanggal_mulai') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Tanggal Akhir</label>
                                        <input type="date" name="tanggal_akhir" class="form-control" value="{{ request('tanggal_akhir') }}">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Supplier</label>
                                        <select name="supplier_id" class="form-select">
                                            <option value="">Semua Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ request('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama_supplier }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label">Status</label>
                                        <select name="status" class="form-select">
                                            <option value="">Semua Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="feather-search me-2"></i>Filter
                                            </button>
                                            <a href="{{ route('pembelian.laporan') }}" class="btn btn-light">
                                                <i class="feather-refresh-cw me-2"></i>Reset
                                            </a>
                                            <button type="submit" name="export" value="pdf" class="btn btn-success">
                                                <i class="feather-download me-2"></i>Export PDF
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistik -->
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
                                    <h6 class="text-muted mb-2">Total Pembelian</h6>
                                    <h3 class="fw-bold mb-0">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</h3>
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
                                    <h6 class="text-muted mb-2">Grand Total</h6>
                                    <h3 class="fw-bold mb-0 text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</h3>
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
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Detail Laporan</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Tanggal</th>
                                            <th>No Nota</th>
                                            <th>Supplier</th>
                                            <th>Status</th>
                                            <th class="text-end">Total Harga</th>
                                            <th class="text-end">Diskon</th>
                                            <th class="text-end">Grand Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pembelian as $index => $item)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $item->tanggal_pembelian->format('d/m/Y') }}</td>
                                                <td>{{ $item->no_nota }}</td>
                                                <td>{{ $item->supplier_nama }}</td>
                                                <td>{!! $item->status_badge !!}</td>
                                                <td class="text-end">{{ $item->total_harga_format }}</td>
                                                <td class="text-end text-success">{{ $item->diskon_format }}</td>
                                                <td class="text-end fw-bold text-primary">{{ $item->grand_total_format }}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center py-5">
                                                    <i class="feather-inbox fs-1 text-muted"></i>
                                                    <p class="mt-3 text-muted">Tidak ada data</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($pembelian->count() > 0)
                                        <tfoot class="bg-light">
                                            <tr>
                                                <th colspan="5" class="text-end">TOTAL:</th>
                                                <th class="text-end">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</th>
                                                <th class="text-end text-success">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</th>
                                                <th class="text-end text-primary">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
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

@push('styles')
<style>
.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}
</style>
@endpush
@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Detail Transaksi Penjualan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="d-flex gap-2">
                    <a href="{{ route('penjualan.cetak', $penjualan->id) }}" class="btn btn-primary" target="_blank">
                        <i class="feather-printer me-2"></i>Cetak Nota
                    </a>
                    @if($penjualan->status_pembayaran === 'belum_lunas')
                        <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning">
                            <i class="feather-edit me-2"></i>Edit
                        </a>
                    @endif
                    <a href="{{ route('penjualan.index') }}" class="btn btn-light">
                        <i class="feather-arrow-left me-2"></i>Kembali
                    </a>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="row">
                <!-- Left Side - Detail Transaksi -->
                <div class="col-lg-8">
                    <!-- Informasi Transaksi -->
                    <div class="card stretch stretch-full mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Transaksi</h5>
                            <div class="card-header-action">
                                {!! $penjualan->status_pembayaran_badge !!}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="40%" class="text-muted">No Nota</td>
                                            <td width="5%">:</td>
                                            <td class="fw-bold text-primary">{{ $penjualan->no_nota }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Tanggal</td>
                                            <td>:</td>
                                            <td>{{ $penjualan->tanggal_penjualan->format('d F Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Kasir</td>
                                            <td>:</td>
                                            <td>{{ $penjualan->user->name ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="col-md-6">
                                    <table class="table table-borderless table-sm">
                                        <tr>
                                            <td width="40%" class="text-muted">Pelanggan</td>
                                            <td width="5%">:</td>
                                            <td>
                                                @if($penjualan->pelanggan)
                                                    <div class="fw-bold">{{ $penjualan->pelanggan->nama_pelanggan }}</div>
                                                    <small class="text-muted">{{ $penjualan->pelanggan->no_telepon }}</small>
                                                @else
                                                    <span class="badge bg-soft-secondary text-secondary">Umum</span>
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Metode Pembayaran</td>
                                            <td>:</td>
                                            <td>{!! $penjualan->metode_pembayaran_badge !!}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Status</td>
                                            <td>:</td>
                                            <td>{!! $penjualan->status_pembayaran_badge !!}</td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Daftar Obat -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Obat</h5>
                            <div class="card-header-action">
                                <span class="badge bg-soft-info text-info">
                                    {{ $penjualan->details->count() }} Item
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th width="5%">No</th>
                                            <th width="35%">Nama Obat</th>
                                            <th width="15%" class="text-end">Harga</th>
                                            <th width="10%" class="text-center">Jumlah</th>
                                            <th width="15%" class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($penjualan->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-bold">{{ $detail->obat->nama_obat }}</div>
                                                    <small class="text-muted">{{ $detail->obat->kode_obat }}</small>
                                                </td>
                                                <td class="text-end">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                                                <td class="text-center">
                                                    <span class="badge bg-soft-primary text-primary">
                                                        {{ $detail->jumlah }} {{ $detail->obat->satuan }}
                                                    </span>
                                                </td>
                                                <td class="text-end fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Side - Summary -->
                <div class="col-lg-4">
                    <!-- Ringkasan Pembayaran -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Ringkasan Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <!-- Total Harga -->
                            <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                <span class="text-muted">Total Harga</span>
                                <span class="fw-bold">Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</span>
                            </div>

                            <!-- Diskon -->
                            @if($penjualan->diskon > 0)
                                <div class="d-flex justify-content-between mb-3 pb-3 border-bottom">
                                    <span class="text-muted">Diskon</span>
                                    <span class="text-success fw-bold">- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</span>
                                </div>
                            @endif

                            <!-- Grand Total -->
                            <div class="d-flex justify-content-between mb-4">
                                <h5 class="mb-0">Grand Total</h5>
                                <h4 class="mb-0 text-primary">Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}</h4>
                            </div>

                            <hr>

                            <!-- Status Badge -->
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted">Status Pembayaran</span>
                                    {!! $penjualan->status_pembayaran_badge !!}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted">Metode Pembayaran</span>
                                    {!! $penjualan->metode_pembayaran_badge !!}
                                </div>
                            </div>

                            <hr>

                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <a href="{{ route('penjualan.cetak', $penjualan->id) }}" class="btn btn-primary" target="_blank">
                                    <i class="feather-printer me-2"></i>Cetak Nota
                                </a>
                                @if($penjualan->status_pembayaran === 'belum_lunas')
                                    <a href="{{ route('penjualan.edit', $penjualan->id) }}" class="btn btn-warning">
                                        <i class="feather-edit me-2"></i>Edit Transaksi
                                    </a>
                                    <form action="{{ route('penjualan.destroy', $penjualan->id) }}" method="POST" id="deleteForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="feather-trash-2 me-2"></i>Hapus Transaksi
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('penjualan.index') }}" class="btn btn-light">
                                    <i class="feather-arrow-left me-2"></i>Kembali ke Daftar
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Tambahan -->
                    <div class="card stretch stretch-full mt-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Tambahan</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Dibuat Pada</small>
                                <div class="fw-bold">{{ $penjualan->created_at->format('d F Y H:i') }}</div>
                            </div>
                            @if($penjualan->updated_at != $penjualan->created_at)
                                <div class="mb-3">
                                    <small class="text-muted d-block mb-1">Terakhir Diubah</small>
                                    <div class="fw-bold">{{ $penjualan->updated_at->format('d F Y H:i') }}</div>
                                </div>
                            @endif
                            <div>
                                <small class="text-muted d-block mb-1">Total Item</small>
                                <div class="fw-bold">{{ $penjualan->details->sum('jumlah') }} Item</div>
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
.table-borderless td {
    padding: 0.5rem 0.75rem;
}

.card-header-action .badge {
    font-size: 0.875rem;
    padding: 0.5rem 1rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

.border-bottom {
    border-color: #e9ecef !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Delete Confirmation
    $('#deleteForm').on('submit', function(e) {
        e.preventDefault();
        
        if (confirm('Yakin ingin menghapus transaksi ini? Data yang sudah dihapus tidak dapat dikembalikan.')) {
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
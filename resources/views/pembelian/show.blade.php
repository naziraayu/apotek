@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Detail Pembelian</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('pembelian.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Kembali
                        </a>
                        <a href="{{ route('pembelian.cetak', $pembelian->id) }}" class="btn btn-secondary" target="_blank">
                            <i class="feather-printer me-2"></i>Cetak
                        </a>
                        @if($pembelian->status === 'pending')
                            <a href="{{ route('pembelian.edit', $pembelian->id) }}" class="btn btn-warning">
                                <i class="feather-edit me-2"></i>Edit
                            </a>
                            <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalSelesai">
                                <i class="feather-check me-2"></i>Selesai
                            </button>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalBatal">
                                <i class="feather-x me-2"></i>Batalkan
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

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

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-8">
                    <!-- Informasi Pembelian -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Pembelian</h5>
                            {!! $pembelian->status_badge !!}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted fs-13">No Nota</label>
                                    <div class="fw-bold">{{ $pembelian->no_nota }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted fs-13">Tanggal Pembelian</label>
                                    <div class="fw-bold">{{ $pembelian->tanggal_pembelian->format('d F Y') }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted fs-13">Supplier</label>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-text avatar-md bg-soft-primary text-primary me-2">
                                            {{ strtoupper(substr($pembelian->supplier_nama, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $pembelian->supplier_nama }}</div>
                                            @if($pembelian->supplier->no_telp)
                                                <small class="text-muted">{{ $pembelian->supplier->no_telp }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="text-muted fs-13">Dibuat Oleh</label>
                                    <div class="fw-bold">{{ $pembelian->user_nama }}</div>
                                    <small class="text-muted">{{ $pembelian->created_at->format('d/m/Y H:i') }}</small>
                                </div>
                                @if($pembelian->keterangan)
                                    <div class="col-12">
                                        <label class="text-muted fs-13">Keterangan</label>
                                        <div class="p-3 bg-light rounded">{{ $pembelian->keterangan }}</div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Detail Item -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Detail Item Pembelian</h5>
                            <span class="badge bg-soft-info text-info">{{ $pembelian->total_item }} item</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Jumlah</th>
                                            <th>Harga Beli</th>
                                            <th>Subtotal</th>
                                            <th>Exp Date</th>
                                            <th>Batch</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($pembelian->details as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-text avatar-sm bg-soft-success text-success me-2">
                                                            {{ strtoupper(substr($detail->obat->nama_obat, 0, 2)) }}
                                                        </div>
                                                        <div>
                                                            <div class="fw-bold">{{ $detail->obat->nama_obat }}</div>
                                                            <small class="text-muted">{{ $detail->obat->kode_obat }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-info text-info">
                                                        {{ $detail->jumlah }} {{ $detail->obat->satuan }}
                                                    </span>
                                                </td>
                                                <td>Rp {{ number_format($detail->harga_beli, 0, ',', '.') }}</td>
                                                <td class="fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                                                <td>
                                                    @if($detail->tanggal_kadaluarsa)
                                                        <span class="badge bg-soft-warning text-warning">
                                                            {{ \Carbon\Carbon::parse($detail->tanggal_kadaluarsa)->format('d/m/Y') }}
                                                        </span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($detail->no_batch)
                                                        <span class="badge bg-soft-secondary text-secondary">{{ $detail->no_batch }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Summary -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Ringkasan Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Total Harga:</span>
                                <span class="fw-bold">{{ $pembelian->total_harga_format }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-3">
                                <span class="text-muted">Diskon:</span>
                                <span class="text-success">{{ $pembelian->diskon_format }}</span>
                            </div>
                            <div class="border-top pt-3">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">Grand Total:</h5>
                                    <h4 class="mb-0 text-primary">{{ $pembelian->grand_total_format }}</h4>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timeline -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Timeline</h5>
                        </div>
                        <div class="card-body">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-primary"></div>
                                <div class="timeline-content">
                                    <h6 class="mb-1">Dibuat</h6>
                                    <p class="fs-13 text-muted mb-0">{{ $pembelian->created_at->format('d F Y, H:i') }}</p>
                                    <small class="text-muted">oleh {{ $pembelian->user_nama }}</small>
                                </div>
                            </div>
                            @if($pembelian->status === 'selesai')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-success"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Diselesaikan</h6>
                                        <p class="fs-13 text-muted mb-0">{{ $pembelian->updated_at->format('d F Y, H:i') }}</p>
                                        <small class="text-success">Stok telah diupdate</small>
                                    </div>
                                </div>
                            @elseif($pembelian->status === 'batal')
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-danger"></div>
                                    <div class="timeline-content">
                                        <h6 class="mb-1">Dibatalkan</h6>
                                        <p class="fs-13 text-muted mb-0">{{ $pembelian->updated_at->format('d F Y, H:i') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</main>

<!-- Modal Selesai -->
<div class="modal fade" id="modalSelesai" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Selesai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="selesai">
                <div class="modal-body">
                    <div class="alert alert-info">
                        <i class="feather-info me-2"></i>
                        Dengan menyelesaikan pembelian ini, stok obat akan otomatis bertambah sesuai dengan jumlah pembelian.
                    </div>
                    <p>Apakah Anda yakin ingin menyelesaikan pembelian ini?</p>
                    <ul class="list-unstyled">
                        <li><i class="feather-check text-success me-2"></i>No Nota: <strong>{{ $pembelian->no_nota }}</strong></li>
                        <li><i class="feather-check text-success me-2"></i>Total: <strong>{{ $pembelian->grand_total_format }}</strong></li>
                        <li><i class="feather-check text-success me-2"></i>Jumlah Item: <strong>{{ $pembelian->total_item }}</strong></li>
                    </ul>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success">Ya, Selesaikan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Batal -->
<div class="modal fade" id="modalBatal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Konfirmasi Batalkan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('pembelian.update', $pembelian->id) }}" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" name="action" value="batal">
                <div class="modal-body">
                    <div class="alert alert-danger">
                        <i class="feather-alert-triangle me-2"></i>
                        Pembelian yang dibatalkan tidak dapat dikembalikan!
                    </div>
                    <p>Apakah Anda yakin ingin membatalkan pembelian ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tidak</button>
                    <button type="submit" class="btn btn-danger">Ya, Batalkan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.timeline-item {
    position: relative;
    padding-left: 30px;
    margin-bottom: 20px;
}

.timeline-item:last-child {
    margin-bottom: 0;
}

.timeline-marker {
    position: absolute;
    left: 0;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px currentColor;
}

.timeline-item:not(:last-child)::before {
    content: '';
    position: absolute;
    left: 5px;
    top: 17px;
    bottom: -20px;
    width: 2px;
    background: #e0e0e0;
}

.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
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
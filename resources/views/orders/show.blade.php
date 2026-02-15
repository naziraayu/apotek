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
                    <h5 class="m-b-10">Detail Pesanan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.orders.index') }}">Pesanan</a></li>
                    <li class="breadcrumb-item active">{{ $order->no_nota }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('shop.orders.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Kembali
                        </a>
                        @if($order->status_pembayaran == 'pending')
                            <button type="button" class="btn btn-danger" onclick="cancelOrder()">
                                <i class="feather-x-circle me-2"></i>Batalkan Pesanan
                            </button>
                        @endif
                    </div>
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
                <!-- Order Details -->
                <div class="col-lg-8">
                    <!-- Order Info Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Pesanan</h5>
                            <div class="card-header-action">
                                {!! $order->status_pembayaran_badge !!}
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                            <i class="feather-file-text"></i>
                                        </div>
                                        <div>
                                            <div class="fs-12 text-muted mb-1">Nomor Pesanan</div>
                                            <div class="fw-bold">{{ $order->no_nota }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar-text avatar-md bg-soft-info text-info">
                                            <i class="feather-calendar"></i>
                                        </div>
                                        <div>
                                            <div class="fs-12 text-muted mb-1">Tanggal Pesanan</div>
                                            <div class="fw-bold">{{ $order->tanggal_penjualan->format('d F Y, H:i') }} WIB</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar-text avatar-md bg-soft-success text-success">
                                            <i class="feather-credit-card"></i>
                                        </div>
                                        <div>
                                            <div class="fs-12 text-muted mb-1">Metode Pembayaran</div>
                                            <div>{!! $order->metode_pembayaran_badge !!}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start gap-3">
                                        <div class="avatar-text avatar-md bg-soft-warning text-warning">
                                            <i class="feather-box"></i>
                                        </div>
                                        <div>
                                            <div class="fs-12 text-muted mb-1">Total Item</div>
                                            <div class="fw-bold">{{ $order->total_item }} item</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            @if($order->catatan)
                                <hr class="my-4">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="avatar-text avatar-md bg-soft-secondary text-secondary">
                                        <i class="feather-message-square"></i>
                                    </div>
                                    <div class="flex-grow-1">
                                        <div class="fs-12 text-muted mb-1">Catatan</div>
                                        <div class="text-muted">{{ $order->catatan }}</div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Item</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Produk</th>
                                            <th class="text-center">Jumlah</th>
                                            <th class="text-end">Harga</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($order->details as $detail)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="avatar-text avatar-md bg-soft-primary text-primary">
                                                            <i class="feather-box"></i>
                                                        </div>
                                                        <div>
                                                            <div class="fw-semibold">{{ $detail->obat_nama }}</div>
                                                            <div class="fs-11 text-muted">{{ $detail->harga_satuan_format }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-soft-info text-info">{{ $detail->jumlah }}x</span>
                                                </td>
                                                <td class="text-end">{{ $detail->harga_satuan_format }}</td>
                                                <td class="text-end fw-bold">{{ $detail->subtotal_format }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Summary -->
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Ringkasan Pembayaran</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-muted">Subtotal</span>
                                <span class="fw-semibold">{{ $order->total_harga_format }}</span>
                            </div>
                            @if($order->diskon > 0)
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Diskon</span>
                                    <span class="text-danger">- {{ $order->diskon_format }}</span>
                                </div>
                            @endif
                            <hr class="my-3">
                            <div class="d-flex justify-content-between">
                                <span class="fs-5 fw-bold">Total Pembayaran</span>
                                <span class="fs-4 fw-bold text-primary">{{ $order->grand_total_format }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <!-- Status Card -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Status Pesanan</h5>
                        </div>
                        <div class="card-body text-center">
                            @if($order->status_pembayaran == 'pending')
                                <div class="avatar-text avatar-xl bg-soft-warning text-warning mb-3">
                                    <i class="feather-clock fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Menunggu Verifikasi</h6>
                                <p class="text-muted fs-12 mb-3">
                                    Pesanan Anda sedang dalam proses verifikasi oleh apoteker kami. 
                                    Mohon tunggu beberapa saat.
                                </p>
                            @elseif($order->status_pembayaran == 'lunas')
                                <div class="avatar-text avatar-xl bg-soft-success text-success mb-3">
                                    <i class="feather-check-circle fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Pembayaran Lunas</h6>
                                <p class="text-muted fs-12 mb-3">
                                    Pesanan Anda telah diverifikasi dan pembayaran telah diterima. 
                                    Terima kasih!
                                </p>
                            @elseif($order->status_pembayaran == 'batal')
                                <div class="avatar-text avatar-xl bg-soft-danger text-danger mb-3">
                                    <i class="feather-x-circle fs-2"></i>
                                </div>
                                <h6 class="fw-bold mb-2">Pesanan Dibatalkan</h6>
                                <p class="text-muted fs-12 mb-3">
                                    Pesanan ini telah dibatalkan.
                                </p>
                            @endif

                            @if($order->status_pembayaran == 'pending')
                                <button type="button" class="btn btn-primary btn-sm w-100 mb-2" data-bs-toggle="modal" data-bs-target="#reuploadModal">
                                    <i class="feather-upload me-1"></i>Upload Ulang Bukti
                                </button>
                            @endif
                        </div>
                    </div>

                    <!-- Payment Proof -->
                    @if($order->hasBuktiPembayaran())
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Bukti Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="text-center">
                                    <img src="{{ $order->bukti_pembayaran_url }}" 
                                         alt="Bukti Pembayaran" 
                                         class="img-fluid rounded"
                                         style="max-height: 300px; cursor: pointer;"
                                         onclick="viewImage(this.src)">
                                    <div class="mt-2">
                                        <a href="{{ $order->bukti_pembayaran_url }}" 
                                           target="_blank" 
                                           class="btn btn-sm btn-light">
                                            <i class="feather-external-link me-1"></i>Lihat Full
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Help Card -->
                    <div class="card bg-soft-info border-info">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="avatar-text avatar-md bg-info text-white me-3">
                                    <i class="feather-help-circle"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-2">Butuh Bantuan?</h6>
                                    <p class="fs-12 text-muted mb-2">
                                        Jika ada pertanyaan tentang pesanan Anda, silakan hubungi apoteker kami.
                                    </p>
                                    <div class="fs-12">
                                        <i class="feather-phone me-1"></i>
                                        <span class="fw-semibold">0812-3456-7890</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</main>

<!-- Cancel Order Form (Hidden) -->
<form id="cancelOrderForm" method="POST" action="{{ route('shop.orders.cancel', $order->id) }}" style="display: none;">
    @csrf
</form>

<!-- Re-upload Modal -->
<div class="modal fade" id="reuploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Upload Ulang Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('shop.orders.reupload', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Bukti Pembayaran <span class="text-danger">*</span></label>
                        <input type="file" name="bukti_pembayaran" class="form-control" accept="image/*" required>
                        <div class="form-text">
                            Format: JPG, PNG, JPEG. Maksimal 2MB
                        </div>
                    </div>
                    <div class="alert alert-info">
                        <i class="feather-info me-2"></i>
                        Pastikan bukti pembayaran jelas dan dapat terbaca dengan baik.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="feather-upload me-1"></i>Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Image Viewer Modal -->
<div class="modal fade" id="imageModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Bukti Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <img src="" id="modalImage" class="img-fluid" alt="Bukti Pembayaran">
            </div>
        </div>
    </div>
</div>
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

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

.table th {
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

img.img-fluid:hover {
    opacity: 0.8;
    transition: opacity 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder() {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
        document.getElementById('cancelOrderForm').submit();
    }
}

function viewImage(src) {
    document.getElementById('modalImage').src = src;
    new bootstrap.Modal(document.getElementById('imageModal')).show();
}

$(document).ready(function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
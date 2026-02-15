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
                    <h5 class="m-b-10">Pesanan Saya</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item active">Pesanan</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('shop.index') }}" class="btn btn-light">
                            <i class="feather-shopping-bag me-2"></i>Lanjut Belanja
                        </a>
                        <a href="{{ route('shop.cart.index') }}" class="btn btn-primary position-relative">
                            <i class="feather-shopping-cart me-2"></i>
                            <span>Keranjang</span>
                            @if(Session::has('cart') && count(Session::get('cart')) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(Session::get('cart')) }}
                                </span>
                            @endif
                        </a>
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
                <!-- Filter Sidebar -->
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Filter Pesanan</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('shop.orders.index') }}">
                                <!-- Status Filter -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Status</label>
                                    <select name="status" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                            Menunggu Verifikasi
                                        </option>
                                        <option value="lunas" {{ request('status') == 'lunas' ? 'selected' : '' }}>
                                            Lunas
                                        </option>
                                        <option value="batal" {{ request('status') == 'batal' ? 'selected' : '' }}>
                                            Dibatalkan
                                        </option>
                                    </select>
                                </div>

                                <!-- Reset Filter -->
                                <a href="{{ route('shop.orders.index') }}" class="btn btn-light w-100">
                                    <i class="feather-refresh-cw me-2"></i>Reset Filter
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="card mt-3">
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="text-muted fs-13">Total Pesanan</span>
                                    <span class="badge bg-soft-primary text-primary">{{ $orders->total() }}</span>
                                </div>
                            </div>
                            <hr class="my-3">
                            <div class="text-center">
                                <div class="avatar-text avatar-lg bg-soft-success text-success mb-2">
                                    <i class="feather-check-circle"></i>
                                </div>
                                <p class="fs-12 text-muted mb-0">
                                    Terima kasih telah berbelanja di apotek kami!
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Orders List -->
                <div class="col-lg-9 col-md-8">
                    @if($orders->count() > 0)
                        @foreach($orders as $order)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <!-- Order Info -->
                                        <div class="col-lg-8 mb-3 mb-lg-0">
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="avatar-text avatar-lg bg-soft-primary text-primary">
                                                    <i class="feather-package"></i>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <h6 class="fw-bold mb-0">{{ $order->no_nota }}</h6>
                                                        {!! $order->status_pembayaran_badge !!}
                                                    </div>
                                                    <div class="text-muted fs-12 mb-2">
                                                        <i class="feather-calendar me-1"></i>
                                                        {{ $order->tanggal_penjualan->format('d F Y') }}
                                                        <span class="mx-2">•</span>
                                                        <i class="feather-clock me-1"></i>
                                                        {{ $order->tanggal_penjualan->format('H:i') }} WIB
                                                    </div>
                                                    <div class="text-muted fs-12 mb-2">
                                                        <i class="feather-box me-1"></i>
                                                        {{ $order->total_item }} item
                                                    </div>
                                                    <div class="text-muted fs-12">
                                                        {!! $order->metode_pembayaran_badge !!}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Order Total & Actions -->
                                        <div class="col-lg-4 text-lg-end">
                                            <div class="mb-3">
                                                <div class="fs-12 text-muted mb-1">Total Pembayaran</div>
                                                <div class="fs-4 fw-bold text-primary">
                                                    {{ $order->grand_total_format }}
                                                </div>
                                            </div>
                                            <div class="d-flex gap-2 justify-content-lg-end">
                                                <a href="{{ route('shop.orders.show', $order->id) }}" class="btn btn-primary btn-sm">
                                                    <i class="feather-eye me-1"></i>Detail
                                                </a>
                                                @if($order->status_pembayaran == 'pending')
                                                    <button type="button" class="btn btn-danger btn-sm" onclick="cancelOrder({{ $order->id }})">
                                                        <i class="feather-x-circle me-1"></i>Batal
                                                    </button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Order Items Preview (max 3) -->
                                    <hr class="my-3">
                                    <div class="row g-2">
                                        @foreach($order->details->take(3) as $detail)
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center gap-2 p-2 bg-light rounded">
                                                    <div class="avatar-text avatar-sm bg-white text-primary">
                                                        <i class="feather-package"></i>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <div class="fs-13 fw-semibold text-truncate">
                                                            {{ Str::limit($detail->obat_nama, 20) }}
                                                        </div>
                                                        <div class="fs-11 text-muted">
                                                            {{ $detail->jumlah }}x {{ $detail->harga_satuan_format }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                        @if($order->details->count() > 3)
                                            <div class="col-md-4">
                                                <div class="d-flex align-items-center justify-content-center h-100 p-2 bg-light rounded">
                                                    <span class="text-muted fs-12">
                                                        +{{ $order->details->count() - 3 }} item lainnya
                                                    </span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        <!-- Pagination -->
                        @if($orders->hasPages())
                            <div class="card">
                                <div class="card-footer">
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div>
                                            <span class="text-muted fs-13">
                                                Menampilkan {{ $orders->firstItem() ?? 0 }} sampai {{ $orders->lastItem() ?? 0 }} dari {{ $orders->total() }} pesanan
                                            </span>
                                        </div>
                                        <nav aria-label="Pagination">
                                            <ul class="pagination pagination-sm mb-0">
                                                {{-- Previous --}}
                                                @if ($orders->onFirstPage())
                                                    <li class="page-item disabled">
                                                        <span class="page-link">‹</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $orders->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                                    </li>
                                                @endif

                                                {{-- Pagination Elements --}}
                                                @foreach(range(1, $orders->lastPage()) as $page)
                                                    @if ($page == $orders->currentPage())
                                                        <li class="page-item active">
                                                            <span class="page-link">{{ $page }}</span>
                                                        </li>
                                                    @else
                                                        <li class="page-item">
                                                            <a class="page-link" href="{{ $orders->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach

                                                {{-- Next --}}
                                                @if ($orders->hasMorePages())
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $orders->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
                                                    </li>
                                                @else
                                                    <li class="page-item disabled">
                                                        <span class="page-link">›</span>
                                                    </li>
                                                @endif
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @else
                        <!-- Empty State -->
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="avatar-text avatar-xxl bg-soft-secondary text-secondary mb-4" style="width: 120px; height: 120px; font-size: 4rem; margin: 0 auto;">
                                    <i class="feather-package"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Belum Ada Pesanan</h4>
                                <p class="text-muted mb-4">
                                    Anda belum memiliki riwayat pesanan.<br>
                                    Mulai belanja sekarang dan buat pesanan pertama Anda!
                                </p>
                                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                                    <i class="feather-shopping-bag me-2"></i>Mulai Belanja
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        <!-- [ Main Content ] end -->
    </div>
</main>

<!-- Cancel Order Form (Hidden) -->
<form id="cancelOrderForm" method="POST" style="display: none;">
    @csrf
</form>
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->
@endsection

@push('styles')
<style>
.card {
    transition: all 0.3s ease;
}

.card:hover {
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.bg-light {
    background-color: #f8f9fa !important;
}
</style>
@endpush

@push('scripts')
<script>
function cancelOrder(orderId) {
    if (confirm('Apakah Anda yakin ingin membatalkan pesanan ini? Tindakan ini tidak dapat dibatalkan.')) {
        const form = document.getElementById('cancelOrderForm');
        form.action = '/shop/orders/' + orderId + '/cancel';
        form.submit();
    }
}

$(document).ready(function() {
    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
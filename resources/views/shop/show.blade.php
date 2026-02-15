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
                    <h5 class="m-b-10">Detail Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Katalog</a></li>
                    <li class="breadcrumb-item active">{{ $obat->nama_obat }}</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('shop.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Kembali
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
                <!-- Main Product Info -->
                <div class="col-lg-8">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <!-- Product Image/Icon -->
                                <div class="col-md-4 text-center mb-4 mb-md-0">
                                    <div class="avatar-text avatar-xxl bg-soft-primary text-primary mb-3" style="width: 150px; height: 150px; font-size: 4rem; margin: 0 auto;">
                                        <i class="feather-box"></i>
                                    </div>
                                    <span class="badge bg-soft-primary text-primary px-3 py-2">
                                        {{ $obat->kategori_nama }}
                                    </span>
                                </div>

                                <!-- Product Details -->
                                <div class="col-md-8">
                                    <h2 class="fw-bold mb-3">{{ $obat->nama_obat }}</h2>
                                    
                                    <!-- Status Badges -->
                                    <div class="d-flex gap-2 mb-3">
                                        @if($obat->stok > $obat->stok_minimum)
                                            <span class="badge bg-soft-success text-success">
                                                <i class="feather-check-circle me-1"></i>Tersedia
                                            </span>
                                        @elseif($obat->stok > 0)
                                            <span class="badge bg-soft-warning text-warning">
                                                <i class="feather-alert-triangle me-1"></i>Stok Terbatas
                                            </span>
                                        @endif

                                        @if($obat->isNearExpired())
                                            <span class="badge bg-soft-warning text-warning">
                                                <i class="feather-alert-circle me-1"></i>Hampir Kadaluarsa
                                            </span>
                                        @endif
                                    </div>

                                    <!-- Price -->
                                    <div class="mb-4">
                                        <div class="fs-12 text-muted mb-1">Harga Per {{ $obat->satuan }}</div>
                                        <h3 class="text-primary fw-bold mb-0">{{ $obat->harga_jual_format }}</h3>
                                    </div>

                                    <!-- Description -->
                                    @if($obat->deskripsi)
                                        <div class="mb-4">
                                            <h6 class="fw-bold mb-2">Deskripsi</h6>
                                            <p class="text-muted">{{ $obat->deskripsi }}</p>
                                        </div>
                                    @endif

                                    <!-- Stock Info -->
                                    <div class="mb-4">
                                        <h6 class="fw-bold mb-3">Informasi Stok</h6>
                                        <div class="row g-3">
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-text avatar-md bg-soft-info text-info me-2">
                                                        <i class="feather-package"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fs-11 text-muted">Stok Tersedia</div>
                                                        <div class="fw-bold">{{ $obat->stok }} {{ $obat->satuan }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="d-flex align-items-center">
                                                    <div class="avatar-text avatar-md bg-soft-warning text-warning me-2">
                                                        <i class="feather-alert-triangle"></i>
                                                    </div>
                                                    <div>
                                                        <div class="fs-11 text-muted">Stok Minimum</div>
                                                        <div class="fw-bold">{{ $obat->stok_minimum }} {{ $obat->satuan }}</div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Add to Cart Form -->
                                    <form action="{{ route('shop.cart.add') }}" method="POST" class="add-to-cart-form">
                                        @csrf
                                        <input type="hidden" name="obat_id" value="{{ $obat->id }}">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-bold">Jumlah</label>
                                                <input type="number" name="jumlah" class="form-control" value="1" min="1" max="{{ $obat->stok }}" required>
                                            </div>
                                            <div class="col-md-8 d-flex align-items-end">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="feather-shopping-cart me-2"></i>Tambah ke Keranjang
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Info Sidebar -->
                <div class="col-lg-4">
                    <!-- Product Specifications -->
                    <div class="card mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Spesifikasi Produk</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group list-group-flush">
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Kategori</span>
                                    <span class="fw-bold">{{ $obat->kategori_nama }}</span>
                                </div>
                                <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                    <span class="text-muted">Satuan</span>
                                    <span class="fw-bold">{{ $obat->satuan }}</span>
                                </div>
                                @if($obat->no_batch)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="text-muted">No. Batch</span>
                                        <span class="fw-bold">{{ $obat->no_batch }}</span>
                                    </div>
                                @endif
                                @if($obat->tanggal_kadaluarsa)
                                    <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                        <span class="text-muted">Kadaluarsa</span>
                                        <span class="fw-bold {{ $obat->isNearExpired() ? 'text-warning' : '' }}">
                                            {{ $obat->tanggal_kadaluarsa_format }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Warning Notice -->
                    <div class="card bg-soft-warning border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="avatar-text avatar-md bg-warning text-white me-3">
                                    <i class="feather-alert-triangle"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-2">Perhatian!</h6>
                                    <p class="fs-12 text-muted mb-0">
                                        Konsultasikan dengan apoteker atau dokter sebelum menggunakan obat ini. 
                                        Pastikan Anda membaca aturan pakai dengan benar.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            @if($relatedObats->count() > 0)
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Obat Terkait</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    @foreach($relatedObats as $related)
                                        <div class="col-lg-3 col-md-4 col-sm-6">
                                            <div class="card product-card h-100">
                                                <div class="card-body">
                                                    <span class="badge bg-soft-primary text-primary mb-2">
                                                        {{ $related->kategori_nama }}
                                                    </span>
                                                    <h6 class="fw-bold mb-2">
                                                        <a href="{{ route('shop.show', $related->id) }}" class="text-dark text-decoration-none">
                                                            {{ Str::limit($related->nama_obat, 30) }}
                                                        </a>
                                                    </h6>
                                                    <div class="d-flex align-items-center gap-2 mb-2">
                                                        <span class="badge {{ $related->stok > $related->stok_minimum ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                                            Stok: {{ $related->stok }}
                                                        </span>
                                                    </div>
                                                    <div class="fw-bold text-primary mb-3">{{ $related->harga_jual_format }}</div>
                                                    <a href="{{ route('shop.show', $related->id) }}" class="btn btn-primary btn-sm w-100">
                                                        <i class="feather-eye me-1"></i>Lihat Detail
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
.product-card {
    transition: all 0.3s ease;
    border: 1px solid #e9ecef;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
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

.list-group-item {
    border: none;
    padding: 0.75rem 0;
    border-bottom: 1px solid #e9ecef;
}

.list-group-item:last-child {
    border-bottom: none;
}

.avatar-xxl {
    width: 8rem;
    height: 8rem;
    font-size: 4rem;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Add to cart with loading state
    $('.add-to-cart-form').on('submit', function(e) {
        var $form = $(this);
        var $button = $form.find('button[type="submit"]');
        
        // Disable button and show loading
        $button.prop('disabled', true);
        $button.html('<i class="feather-loader me-1"></i>Menambahkan...');
    });

    // Validate quantity input
    $('input[name="jumlah"]').on('input', function() {
        var max = parseInt($(this).attr('max'));
        var val = parseInt($(this).val());
        
        if (val > max) {
            $(this).val(max);
            alert('Jumlah melebihi stok yang tersedia!');
        }
        
        if (val < 1) {
            $(this).val(1);
        }
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
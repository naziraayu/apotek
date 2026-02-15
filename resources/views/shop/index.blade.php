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
                    <h5 class="m-b-10">Katalog Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item active">Katalog</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('shop.cart.index') }}" class="btn btn-primary position-relative">
                            <i class="feather-shopping-cart me-2"></i>
                            <span>Keranjang</span>
                            @if(Session::has('cart') && count(Session::get('cart')) > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ count(Session::get('cart')) }}
                                </span>
                            @endif
                        </a>
                        <a href="{{ route('shop.orders.index') }}" class="btn btn-secondary">
                            <i class="feather-package me-2"></i>
                            <span>Pesanan Saya</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <!-- Sidebar Filter -->
                <div class="col-lg-3 col-md-4 mb-4">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Filter Obat</h5>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="{{ route('shop.index') }}" id="filterForm">
                                <!-- Kategori -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Kategori</label>
                                    <select name="kategori_id" class="form-select" onchange="this.form.submit()">
                                        <option value="">Semua Kategori</option>
                                        @foreach($kategoris as $kategori)
                                            <option value="{{ $kategori->id }}" {{ request('kategori_id') == $kategori->id ? 'selected' : '' }}>
                                                {{ $kategori->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Sort -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Urutkan</label>
                                    <select name="sort" class="form-select" onchange="this.form.submit()">
                                        <option value="">Terbaru</option>
                                        <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                                        <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                    </select>
                                </div>

                                <!-- Reset Filter -->
                                <a href="{{ route('shop.index') }}" class="btn btn-light w-100">
                                    <i class="feather-refresh-cw me-2"></i>Reset Filter
                                </a>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card stretch stretch-full mt-3">
                        <div class="card-body text-center">
                            <div class="avatar-text avatar-lg bg-soft-primary text-primary mb-3">
                                <i class="feather-info"></i>
                            </div>
                            <h6>Informasi Penting</h6>
                            <p class="text-muted fs-12 mb-0">
                                Semua obat yang ditampilkan tersedia dan belum kadaluarsa. 
                                Pastikan Anda membaca deskripsi dengan teliti sebelum membeli.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="col-lg-9 col-md-8">
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

                    <!-- Search Bar -->
                    <div class="card mb-3">
                        <div class="card-body">
                            <form method="GET" action="{{ route('shop.index') }}">
                                <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}">
                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                <div class="input-group">
                                    <input type="text" name="search" class="form-control" placeholder="Cari nama obat..." value="{{ request('search') }}">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="feather-search me-2"></i>Cari
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="row g-3">
                        @forelse($obats as $obat)
                            <div class="col-lg-4 col-md-6 col-sm-6">
                                <div class="card product-card h-100">
                                    <div class="card-body">
                                        <!-- Badge Kategori -->
                                        <div class="mb-2">
                                            <span class="badge bg-soft-primary text-primary">
                                                {{ $obat->kategori_nama }}
                                            </span>
                                        </div>

                                        <!-- Nama Obat -->
                                        <h6 class="fw-bold mb-2">
                                            <a href="{{ route('shop.show', $obat->id) }}" class="text-dark text-decoration-none">
                                                {{ $obat->nama_obat }}
                                            </a>
                                        </h6>

                                        <!-- Deskripsi -->
                                        @if($obat->deskripsi)
                                            <p class="text-muted fs-12 mb-2" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                                {{ $obat->deskripsi }}
                                            </p>
                                        @endif

                                        <!-- Info Stok & Satuan -->
                                        <div class="d-flex align-items-center gap-2 mb-3">
                                            <span class="badge {{ $obat->stok > $obat->stok_minimum ? 'bg-soft-success text-success' : 'bg-soft-warning text-warning' }}">
                                                Stok: {{ $obat->stok }} {{ $obat->satuan }}
                                            </span>
                                            @if($obat->isNearExpired())
                                                <span class="badge bg-soft-warning text-warning">
                                                    <i class="feather-alert-circle"></i> Hampir ED
                                                </span>
                                            @endif
                                        </div>

                                        <!-- Harga -->
                                        <div class="d-flex align-items-center justify-content-between mb-3">
                                            <div>
                                                <div class="fs-11 text-muted">Harga</div>
                                                <div class="fs-5 fw-bold text-primary">
                                                    {{ $obat->harga_jual_format }}
                                                </div>
                                                <div class="fs-11 text-muted">per {{ $obat->satuan }}</div>
                                            </div>
                                        </div>

                                        <!-- Form Add to Cart -->
                                        <form action="{{ route('shop.cart.add') }}" method="POST" class="add-to-cart-form">
                                            @csrf
                                            <input type="hidden" name="obat_id" value="{{ $obat->id }}">
                                            <div class="row g-2">
                                                <div class="col-5">
                                                    <input type="number" name="jumlah" class="form-control form-control-sm" value="1" min="1" max="{{ $obat->stok }}" required>
                                                </div>
                                                <div class="col-7">
                                                    <button type="submit" class="btn btn-primary btn-sm w-100">
                                                        <i class="feather-shopping-cart me-1"></i>Tambah
                                                    </button>
                                                </div>
                                            </div>
                                        </form>

                                        <!-- Link Detail -->
                                        <a href="{{ route('shop.show', $obat->id) }}" class="btn btn-light btn-sm w-100 mt-2">
                                            <i class="feather-eye me-1"></i>Lihat Detail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body text-center py-5">
                                        <div class="avatar-text avatar-xl bg-soft-secondary text-secondary mb-3">
                                            <i class="feather-package fs-1"></i>
                                        </div>
                                        <h5>Obat Tidak Ditemukan</h5>
                                        <p class="text-muted mb-0">
                                            Tidak ada obat yang tersedia saat ini atau sesuai dengan filter yang Anda pilih.
                                        </p>
                                        <a href="{{ route('shop.index') }}" class="btn btn-primary mt-3">
                                            <i class="feather-refresh-cw me-2"></i>Lihat Semua Obat
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Pagination -->
                    @if($obats->hasPages())
                        <div class="card mt-4">
                            <div class="card-footer">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <span class="text-muted fs-13">
                                            Menampilkan {{ $obats->firstItem() ?? 0 }} sampai {{ $obats->lastItem() ?? 0 }} dari {{ $obats->total() }} obat
                                        </span>
                                    </div>
                                    <nav aria-label="Pagination">
                                        <ul class="pagination pagination-sm mb-0">
                                            {{-- Previous --}}
                                            @if ($obats->onFirstPage())
                                                <li class="page-item disabled">
                                                    <span class="page-link">‹</span>
                                                </li>
                                            @else
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $obats->appends(request()->query())->previousPageUrl() }}" rel="prev">‹</a>
                                                </li>
                                            @endif

                                            {{-- Pagination Elements --}}
                                            @foreach(range(1, $obats->lastPage()) as $page)
                                                @if ($page == $obats->currentPage())
                                                    <li class="page-item active">
                                                        <span class="page-link">{{ $page }}</span>
                                                    </li>
                                                @else
                                                    <li class="page-item">
                                                        <a class="page-link" href="{{ $obats->appends(request()->query())->url($page) }}">{{ $page }}</a>
                                                    </li>
                                                @endif
                                            @endforeach

                                            {{-- Next --}}
                                            @if ($obats->hasMorePages())
                                                <li class="page-item">
                                                    <a class="page-link" href="{{ $obats->appends(request()->query())->nextPageUrl() }}" rel="next">›</a>
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

.form-control:focus,
.form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

.btn-sm {
    padding: 0.375rem 0.75rem;
    font-size: 0.875rem;
}

.product-card .card-body {
    padding: 1.25rem;
}

/* Loading animation */
.add-to-cart-form button[type="submit"]:disabled {
    cursor: not-allowed;
    opacity: 0.6;
}

/* Badge notification */
.badge.rounded-pill {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
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
        $button.html('<i class="feather-loader me-1"></i>Loading...');
    });

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

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

    // Smooth scroll to top when changing page
    $('a[href*="#"]').on('click', function(e) {
        if (this.hash !== '') {
            e.preventDefault();
            var hash = this.hash;
            $('html, body').animate({
                scrollTop: $(hash).offset().top - 100
            }, 800);
        }
    });
});
</script>
@endpush
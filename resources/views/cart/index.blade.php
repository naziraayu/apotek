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
                    <h5 class="m-b-10">Keranjang Belanja</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item active">Keranjang</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('shop.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Lanjut Belanja
                        </a>
                        <a href="{{ route('shop.orders.index') }}" class="btn btn-secondary">
                            <i class="feather-package me-2"></i>Pesanan Saya
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

            @if(empty($cart) || count($cart) == 0)
                <!-- Empty Cart -->
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <div class="avatar-text avatar-xxl bg-soft-primary text-primary mb-4" style="width: 120px; height: 120px; font-size: 4rem; margin: 0 auto;">
                                    <i class="feather-shopping-cart"></i>
                                </div>
                                <h4 class="fw-bold mb-3">Keranjang Belanja Kosong</h4>
                                <p class="text-muted mb-4">
                                    Anda belum menambahkan produk apapun ke keranjang.<br>
                                    Mulai belanja sekarang dan temukan obat yang Anda butuhkan!
                                </p>
                                <a href="{{ route('shop.index') }}" class="btn btn-primary btn-lg">
                                    <i class="feather-shopping-bag me-2"></i>Mulai Belanja
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- Cart Content -->
                <div class="row">
                    <!-- Cart Items -->
                    <div class="col-lg-8">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Item di Keranjang ({{ count($cart) }} produk)</h5>
                                <div class="card-header-action">
                                    <form action="{{ route('shop.cart.clear') }}" method="POST" id="clearCartForm">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-sm btn-danger" onclick="confirmClearCart()">
                                            <i class="feather-trash-2 me-1"></i>Kosongkan Keranjang
                                        </button>
                                    </form>
                                </div>
                            </div>
                            <div class="card-body">
                                @foreach($cart as $id => $item)
                                    <div class="cart-item border-bottom pb-4 mb-4">
                                        <div class="row align-items-center">
                                            <!-- Product Image/Icon -->
                                            <div class="col-md-2 col-sm-3 text-center mb-3 mb-md-0">
                                                <div class="avatar-text avatar-lg bg-soft-primary text-primary" style="width: 80px; height: 80px; font-size: 2rem; margin: 0 auto;">
                                                    <i class="feather-box"></i>
                                                </div>
                                            </div>

                                            <!-- Product Info -->
                                            <div class="col-md-4 col-sm-9 mb-3 mb-md-0">
                                                <h6 class="fw-bold mb-2">{{ $item['nama'] }}</h6>
                                                <div class="text-muted fs-12 mb-1">
                                                    <span class="badge bg-soft-info text-info">{{ $item['satuan'] }}</span>
                                                </div>
                                                <div class="text-primary fw-bold">
                                                    {{ 'Rp ' . number_format($item['harga'], 0, ',', '.') }}
                                                </div>
                                            </div>

                                            <!-- Quantity Control -->
                                            <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
                                                <form action="{{ route('shop.cart.update', $id) }}" method="POST" class="update-cart-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="input-group input-group-sm">
                                                        <button type="button" class="btn btn-outline-secondary btn-minus" data-id="{{ $id }}">
                                                            <i class="feather-minus"></i>
                                                        </button>
                                                        <input type="number" name="jumlah" class="form-control text-center quantity-input" 
                                                               value="{{ $item['jumlah'] }}" min="1" data-id="{{ $id }}" required>
                                                        <button type="button" class="btn btn-outline-secondary btn-plus" data-id="{{ $id }}">
                                                            <i class="feather-plus"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>

                                            <!-- Subtotal & Remove -->
                                            <div class="col-md-3 col-sm-6 text-md-end">
                                                <div class="fw-bold text-dark mb-2">
                                                    {{ 'Rp ' . number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                                </div>
                                                <form action="{{ route('shop.cart.remove', $id) }}" method="POST" class="remove-cart-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" class="btn btn-sm btn-light text-danger remove-item">
                                                        <i class="feather-trash-2 me-1"></i>Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Continue Shopping -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <a href="{{ route('shop.index') }}" class="text-decoration-none">
                                    <i class="feather-arrow-left me-2"></i>Lanjut Belanja
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Cart Summary -->
                    <div class="col-lg-4">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Ringkasan Belanja</h5>
                            </div>
                            <div class="card-body">
                                <!-- Items Summary -->
                                <div class="mb-3">
                                    @foreach($cart as $item)
                                        <div class="d-flex justify-content-between mb-2">
                                            <span class="text-muted fs-13">
                                                {{ Str::limit($item['nama'], 25) }} (x{{ $item['jumlah'] }})
                                            </span>
                                            <span class="fw-semibold">
                                                {{ 'Rp ' . number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                            </span>
                                        </div>
                                    @endforeach
                                </div>

                                <hr class="my-3">

                                <!-- Total Items -->
                                <div class="d-flex justify-content-between mb-2">
                                    <span class="text-muted">Total Item</span>
                                    <span class="fw-semibold">{{ count($cart) }} produk</span>
                                </div>

                                <!-- Subtotal -->
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Subtotal</span>
                                    <span class="fw-semibold">{{ 'Rp ' . number_format($total, 0, ',', '.') }}</span>
                                </div>

                                <hr class="my-3">

                                <!-- Grand Total -->
                                <div class="d-flex justify-content-between mb-4">
                                    <span class="fs-5 fw-bold">Total Pembayaran</span>
                                    <span class="fs-4 fw-bold text-primary">
                                        {{ 'Rp ' . number_format($total, 0, ',', '.') }}
                                    </span>
                                </div>

                                <!-- Checkout Button -->
                                <a href="{{ route('shop.checkout') }}" class="btn btn-primary w-100 btn-lg">
                                    <i class="feather-credit-card me-2"></i>Lanjut ke Pembayaran
                                </a>
                            </div>
                        </div>

                        <!-- Info Card -->
                        <div class="card mt-3">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-md bg-soft-info text-info me-3">
                                        <i class="feather-info"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-2">Informasi Penting</h6>
                                        <p class="fs-12 text-muted mb-0">
                                            Pastikan jumlah obat yang Anda pesan sudah sesuai. 
                                            Anda dapat mengubah jumlah atau menghapus item sebelum melanjutkan ke pembayaran.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Promo Info (Optional) -->
                        <div class="card mt-3 bg-soft-success border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-md bg-success text-white me-3">
                                        <i class="feather-tag"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-2 text-success">Belanja Aman & Terpercaya</h6>
                                        <p class="fs-12 text-muted mb-0">
                                            Semua obat dijamin asli dan tersedia stok. 
                                            Pembayaran akan diverifikasi oleh apoteker kami.
                                        </p>
                                    </div>
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
.cart-item {
    transition: all 0.3s ease;
}

.cart-item:hover {
    background-color: #f8f9fa;
    border-radius: 0.375rem;
    padding: 1rem;
}

.cart-item:last-child {
    border-bottom: none !important;
    margin-bottom: 0 !important;
    padding-bottom: 0 !important;
}

.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}

.quantity-input {
    max-width: 80px;
    text-align: center;
}

.input-group-sm > .btn {
    padding: 0.25rem 0.5rem;
}

.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

.card-header-action form {
    margin: 0;
}

.btn-minus, .btn-plus {
    width: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Loading animation */
.updating {
    opacity: 0.6;
    pointer-events: none;
}

/* Remove button hover effect */
.remove-item:hover {
    background-color: #dc3545 !important;
    color: white !important;
    border-color: #dc3545 !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Plus button
    $('.btn-plus').on('click', function() {
        var id = $(this).data('id');
        var $input = $('.quantity-input[data-id="' + id + '"]');
        var currentVal = parseInt($input.val());
        $input.val(currentVal + 1);
        updateCart(id, currentVal + 1);
    });

    // Minus button
    $('.btn-minus').on('click', function() {
        var id = $(this).data('id');
        var $input = $('.quantity-input[data-id="' + id + '"]');
        var currentVal = parseInt($input.val());
        if (currentVal > 1) {
            $input.val(currentVal - 1);
            updateCart(id, currentVal - 1);
        }
    });

    // Manual input change
    $('.quantity-input').on('change', function() {
        var id = $(this).data('id');
        var value = parseInt($(this).val());
        if (value < 1) {
            $(this).val(1);
            value = 1;
        }
        updateCart(id, value);
    });

    // Update cart via AJAX
    function updateCart(id, quantity) {
        var $form = $('.update-cart-form').has('.quantity-input[data-id="' + id + '"]');
        
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                _method: 'PUT',
                jumlah: quantity
            },
            success: function(response) {
                // Reload page to update totals
                location.reload();
            },
            error: function(xhr) {
                alert('Gagal mengupdate keranjang. ' + (xhr.responseJSON?.message || 'Silakan coba lagi.'));
                location.reload();
            }
        });
    }

    // Remove item with confirmation
    $('.remove-item').on('click', function() {
        if (confirm('Apakah Anda yakin ingin menghapus item ini dari keranjang?')) {
            $(this).closest('form').submit();
        }
    });

    // Clear cart confirmation
    window.confirmClearCart = function() {
        if (confirm('Apakah Anda yakin ingin mengosongkan keranjang? Semua item akan dihapus.')) {
            $('#clearCartForm').submit();
        }
    };

    // Auto-dismiss alerts after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);

    // Prevent non-numeric input
    $('.quantity-input').on('keypress', function(e) {
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
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
                    <h5 class="m-b-10">Checkout</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('shop.index') }}">Shop</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('shop.cart.index') }}">Keranjang</a></li>
                    <li class="breadcrumb-item active">Checkout</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('shop.cart.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>Kembali ke Keranjang
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('shop.order.store') }}" method="POST" enctype="multipart/form-data" id="checkoutForm">
                @csrf
                <div class="row">
                    <!-- Checkout Form -->
                    <div class="col-lg-8">
                        <!-- Payment Method -->
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Metode Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="tunai" value="tunai" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="tunai">
                                            <i class="feather-dollar-sign fs-4 mb-2 d-block"></i>
                                            <strong class="d-block mb-1">Tunai</strong>
                                            <small class="text-muted">Bayar tunai di apotek</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="transfer" value="transfer" checked required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="transfer">
                                            <i class="feather-credit-card fs-4 mb-2 d-block"></i>
                                            <strong class="d-block mb-1">Transfer Bank</strong>
                                            <small class="text-muted">Transfer ke rekening apotek</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="ewallet" value="e-wallet" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="ewallet">
                                            <i class="feather-smartphone fs-4 mb-2 d-block"></i>
                                            <strong class="d-block mb-1">E-Wallet</strong>
                                            <small class="text-muted">GoPay, OVO, Dana, dll</small>
                                        </label>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="radio" class="btn-check" name="metode_pembayaran" id="kartu_kredit" value="kartu_kredit" required>
                                        <label class="btn btn-outline-primary w-100 py-3" for="kartu_kredit">
                                            <i class="feather-credit-card fs-4 mb-2 d-block"></i>
                                            <strong class="d-block mb-1">Kartu Kredit</strong>
                                            <small class="text-muted">Visa, Mastercard, dll</small>
                                        </label>
                                    </div>
                                </div>
                                @error('metode_pembayaran')
                                    <div class="text-danger fs-12 mt-2">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Bank Information (shown when Transfer selected) -->
                        <div class="card mb-3" id="bankInfo">
                            <div class="card-header bg-primary text-white">
                                <h5 class="card-title text-white mb-0">Informasi Rekening</h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-info mb-3">
                                    <i class="feather-info me-2"></i>
                                    Silakan transfer ke salah satu rekening berikut:
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="avatar-text avatar-sm bg-soft-primary text-primary">
                                                    <i class="feather-credit-card"></i>
                                                </div>
                                                <strong>Bank BCA</strong>
                                            </div>
                                            <div class="fs-13 mb-1">
                                                <span class="text-muted">No. Rekening:</span>
                                                <div class="fw-bold">1234567890</div>
                                            </div>
                                            <div class="fs-13">
                                                <span class="text-muted">Atas Nama:</span>
                                                <div class="fw-bold">Apotek Sehat</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="border rounded p-3">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <div class="avatar-text avatar-sm bg-soft-success text-success">
                                                    <i class="feather-credit-card"></i>
                                                </div>
                                                <strong>Bank Mandiri</strong>
                                            </div>
                                            <div class="fs-13 mb-1">
                                                <span class="text-muted">No. Rekening:</span>
                                                <div class="fw-bold">0987654321</div>
                                            </div>
                                            <div class="fs-13">
                                                <span class="text-muted">Atas Nama:</span>
                                                <div class="fw-bold">Apotek Sehat</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Proof Upload (REQUIRED) -->
                        <div class="card mb-3">
                            <div class="card-header bg-warning text-dark">
                                <h5 class="card-title mb-0">
                                    <i class="feather-upload me-2"></i>Upload Bukti Pembayaran
                                    <span class="badge bg-danger ms-2">WAJIB</span>
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-warning mb-3">
                                    <i class="feather-alert-triangle me-2"></i>
                                    <strong>Perhatian:</strong> Bukti pembayaran WAJIB diupload untuk memproses pesanan Anda.
                                </div>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">
                                        Foto Bukti Pembayaran <span class="text-danger">*</span>
                                    </label>
                                    <input type="file" 
                                           class="form-control @error('bukti_pembayaran') is-invalid @enderror" 
                                           name="bukti_pembayaran" 
                                           id="buktiPembayaran"
                                           accept="image/jpeg,image/png,image/jpg" 
                                           required>
                                    <div class="form-text">
                                        Format: JPG, PNG, JPEG. Maksimal 2MB. 
                                        Pastikan bukti pembayaran jelas dan dapat terbaca.
                                    </div>
                                    @error('bukti_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Image Preview -->
                                <div id="imagePreview" class="d-none">
                                    <label class="form-label fw-bold">Preview:</label>
                                    <div class="border rounded p-2 text-center">
                                        <img src="" id="previewImage" class="img-fluid" style="max-height: 300px;">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes (Optional) -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title">Catatan (Opsional)</h5>
                            </div>
                            <div class="card-body">
                                <textarea name="catatan" 
                                          class="form-control @error('catatan') is-invalid @enderror" 
                                          rows="3" 
                                          placeholder="Tambahkan catatan untuk pesanan Anda (opsional)">{{ old('catatan') }}</textarea>
                                @error('catatan')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="col-lg-4">
                        <div class="card mb-3">
                            <div class="card-header">
                                <h5 class="card-title">Ringkasan Pesanan</h5>
                            </div>
                            <div class="card-body">
                                <!-- Items -->
                                <div class="mb-3">
                                    @foreach($cart as $item)
                                        <div class="d-flex justify-content-between mb-2">
                                            <div class="flex-grow-1">
                                                <div class="fw-semibold fs-13">{{ Str::limit($item['nama'], 30) }}</div>
                                                <div class="text-muted fs-11">
                                                    {{ $item['jumlah'] }}x {{ 'Rp ' . number_format($item['harga'], 0, ',', '.') }}
                                                </div>
                                            </div>
                                            <div class="fw-bold">
                                                {{ 'Rp ' . number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                                            </div>
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

                                <!-- Submit Button -->
                                <button type="submit" class="btn btn-primary w-100 btn-lg" id="submitBtn">
                                    <i class="feather-check-circle me-2"></i>Proses Pesanan
                                </button>
                            </div>
                        </div>

                        <!-- Important Info -->
                        <div class="card mb-3 bg-soft-warning border-warning">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-md bg-warning text-white me-3">
                                        <i class="feather-alert-triangle"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-2">Penting!</h6>
                                        <ul class="fs-12 text-muted mb-0 ps-3">
                                            <li>Upload bukti pembayaran WAJIB untuk memproses pesanan</li>
                                            <li>Pesanan akan diverifikasi oleh apoteker dalam 1x24 jam</li>
                                            <li>Pastikan bukti pembayaran jelas dan dapat terbaca</li>
                                            <li>Anda akan menerima notifikasi setelah pesanan diverifikasi</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Security Info -->
                        <div class="card bg-soft-success border-success">
                            <div class="card-body">
                                <div class="d-flex align-items-start">
                                    <div class="avatar-text avatar-md bg-success text-white me-3">
                                        <i class="feather-shield"></i>
                                    </div>
                                    <div>
                                        <h6 class="fw-bold mb-2">Transaksi Aman</h6>
                                        <p class="fs-12 text-muted mb-0">
                                            Data Anda dilindungi dengan enkripsi. 
                                            Semua pembayaran akan diverifikasi oleh apoteker profesional kami.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
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
.btn-check:checked + .btn-outline-primary {
    background-color: #0d6efd;
    color: white;
    border-color: #0d6efd;
}

.btn-outline-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    transition: all 0.3s ease;
}

.avatar-text {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    border-radius: 0.375rem;
}

#imagePreview img {
    border-radius: 0.375rem;
}

.card {
    transition: all 0.3s ease;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Show/Hide bank info based on payment method
    function toggleBankInfo() {
        const selectedMethod = $('input[name="metode_pembayaran"]:checked').val();
        if (selectedMethod === 'transfer') {
            $('#bankInfo').slideDown();
        } else {
            $('#bankInfo').slideUp();
        }
    }

    // Initial check
    toggleBankInfo();

    // Listen to changes
    $('input[name="metode_pembayaran"]').on('change', function() {
        toggleBankInfo();
    });

    // Image preview
    $('#buktiPembayaran').on('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validate file size (max 2MB)
            if (file.size > 2 * 1024 * 1024) {
                alert('Ukuran file terlalu besar! Maksimal 2MB.');
                $(this).val('');
                $('#imagePreview').addClass('d-none');
                return;
            }

            // Validate file type
            const validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
            if (!validTypes.includes(file.type)) {
                alert('Format file tidak valid! Gunakan JPG, JPEG, atau PNG.');
                $(this).val('');
                $('#imagePreview').addClass('d-none');
                return;
            }

            // Show preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#previewImage').attr('src', e.target.result);
                $('#imagePreview').removeClass('d-none');
            };
            reader.readAsDataURL(file);
        } else {
            $('#imagePreview').addClass('d-none');
        }
    });

    // Form validation before submit
    $('#checkoutForm').on('submit', function(e) {
        const buktiPembayaran = $('#buktiPembayaran').val();
        
        if (!buktiPembayaran) {
            e.preventDefault();
            alert('Bukti pembayaran wajib diupload!');
            $('#buktiPembayaran').focus();
            return false;
        }

        // Show loading on button
        const $btn = $('#submitBtn');
        $btn.prop('disabled', true);
        $btn.html('<i class="feather-loader me-2"></i>Memproses...');
    });

    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut('slow');
    }, 5000);
});
</script>
@endpush
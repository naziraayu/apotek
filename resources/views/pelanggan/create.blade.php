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
                    <h5 class="m-b-10">Tambah Pelanggan</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pelanggan.index') }}">Pelanggan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('pelanggan.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-8 offset-lg-2">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Form Tambah Pelanggan</h5>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('pelanggan.store') }}" method="POST" id="formPelanggan">
                                @csrf
                                
                                <div class="row">
                                    <!-- Nama Pelanggan -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">
                                            Nama Pelanggan <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               name="nama_pelanggan" 
                                               class="form-control @error('nama_pelanggan') is-invalid @enderror" 
                                               value="{{ old('nama_pelanggan') }}"
                                               placeholder="Masukkan nama pelanggan"
                                               required>
                                        @error('nama_pelanggan')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- No Telepon -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            No. Telepon <span class="text-danger">*</span>
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="feather-phone"></i>
                                            </span>
                                            <input type="text" 
                                                   name="no_telp" 
                                                   class="form-control @error('no_telp') is-invalid @enderror" 
                                                   value="{{ old('no_telp') }}"
                                                   placeholder="08xx xxxx xxxx"
                                                   required>
                                            @error('no_telp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Format: 08xxxxxxxxxx</small>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">
                                            Email
                                        </label>
                                        <div class="input-group">
                                            <span class="input-group-text">
                                                <i class="feather-mail"></i>
                                            </span>
                                            <input type="email" 
                                                   name="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   value="{{ old('email') }}"
                                                   placeholder="email@contoh.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Alamat -->
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">
                                            Alamat
                                        </label>
                                        <textarea name="alamat" 
                                                  class="form-control @error('alamat') is-invalid @enderror" 
                                                  rows="3" 
                                                  placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                        @error('alamat')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="border-top pt-3 mt-3">
                                    <div class="d-flex gap-2 justify-content-end">
                                        <a href="{{ route('pelanggan.index') }}" class="btn btn-light">
                                            <i class="feather-x me-2"></i>Batal
                                        </a>
                                        <button type="reset" class="btn btn-warning">
                                            <i class="feather-refresh-cw me-2"></i>Reset
                                        </button>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="feather-save me-2"></i>Simpan
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card border-primary">
                        <div class="card-body">
                            <h6 class="card-title text-primary">
                                <i class="feather-info me-2"></i>Informasi
                            </h6>
                            <ul class="mb-0 ps-3">
                                <li class="mb-2">
                                    <small>Nama pelanggan dan nomor telepon <strong>wajib diisi</strong></small>
                                </li>
                                <li class="mb-2">
                                    <small>Nomor telepon harus <strong>unik</strong> dan tidak boleh sama dengan pelanggan lain</small>
                                </li>
                                <li class="mb-2">
                                    <small>Email bersifat <strong>opsional</strong>, namun jika diisi harus format yang valid</small>
                                </li>
                                <li class="mb-2">
                                    <small>Tanggal pendaftaran akan otomatis diisi dengan tanggal hari ini</small>
                                </li>
                            </ul>
                        </div>
                    </div>
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

@push('scripts')
<script>
$(document).ready(function() {
    // Validasi nomor telepon (hanya angka)
    $('input[name="no_telp"]').on('keypress', function(e) {
        var charCode = (e.which) ? e.which : e.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode > 57)) {
            e.preventDefault();
            return false;
        }
        return true;
    });

    // Auto format nomor telepon
    $('input[name="no_telp"]').on('blur', function() {
        var value = $(this).val();
        // Hapus karakter selain angka
        value = value.replace(/\D/g, '');
        // Pastikan diawali dengan 0
        if (value.length > 0 && value.charAt(0) !== '0') {
            value = '0' + value;
        }
        $(this).val(value);
    });

    // Form validation
    $('#formPelanggan').on('submit', function(e) {
        var noTelp = $('input[name="no_telp"]').val();
        
        // Validasi panjang nomor telepon
        if (noTelp.length < 10 || noTelp.length > 15) {
            e.preventDefault();
            alert('Nomor telepon harus antara 10-15 digit');
            $('input[name="no_telp"]').focus();
            return false;
        }

        // Validasi format email jika diisi
        var email = $('input[name="email"]').val();
        if (email && !isValidEmail(email)) {
            e.preventDefault();
            alert('Format email tidak valid');
            $('input[name="email"]').focus();
            return false;
        }
    });

    // Email validation function
    function isValidEmail(email) {
        var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});
</script>
@endpush
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
                    <h5 class="m-b-10">Tambah Kategori Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kategori-obat.index') }}">Kategori Obat</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('kategori-obat.index') }}" class="btn btn-light">
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
                            <h5 class="card-title">Form Tambah Kategori Obat</h5>
                        </div>
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Terjadi Kesalahan!</strong>
                                    <ul class="mb-0 mt-2">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('kategori-obat.store') }}" method="POST" id="kategoriForm">
                                @csrf
                                
                                <div class="row g-3">
                                    <!-- Nama Kategori -->
                                    <div class="col-md-12">
                                        <label for="nama_kategori" class="form-label">
                                            Nama Kategori <span class="text-danger">*</span>
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('nama_kategori') is-invalid @enderror" 
                                               id="nama_kategori" 
                                               name="nama_kategori" 
                                               placeholder="Contoh: Antibiotik, Vitamin, dll"
                                               value="{{ old('nama_kategori') }}"
                                               required>
                                        @error('nama_kategori')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Nama kategori harus unik</small>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-md-12">
                                        <label for="deskripsi" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                                                  id="deskripsi" 
                                                  name="deskripsi" 
                                                  rows="4" 
                                                  placeholder="Masukkan deskripsi kategori obat (opsional)">{{ old('deskripsi') }}</textarea>
                                        @error('deskripsi')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="text-muted">Maksimal 500 karakter</small>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('kategori-obat.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Info Card -->
                    <div class="card border-dashed border-info bg-soft-info">
                        <div class="card-body">
                            <div class="d-flex align-items-start">
                                <div class="avatar-text bg-info text-white me-3">
                                    <i class="feather-info"></i>
                                </div>
                                <div>
                                    <h6 class="mb-2">Informasi</h6>
                                    <ul class="mb-0 fs-13">
                                        <li>Nama kategori harus unik dan belum terdaftar</li>
                                        <li>Kategori yang sudah dibuat dapat digunakan untuk mengklasifikasikan obat</li>
                                        <li>Deskripsi bersifat opsional namun disarankan untuk memperjelas kategori</li>
                                    </ul>
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
<!--! ================================================================ !-->
<!--! [End] Main Content !-->
<!--! ================================================================ !-->
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#kategoriForm').on('submit', function(e) {
        var nama = $('#nama_kategori').val().trim();
        
        if (nama === '') {
            e.preventDefault();
            alert('Nama kategori harus diisi');
            $('#nama_kategori').focus();
            return false;
        }
    });

    // Auto capitalize first letter
    $('#nama_kategori').on('blur', function() {
        var value = $(this).val();
        if (value) {
            $(this).val(value.charAt(0).toUpperCase() + value.slice(1));
        }
    });

    // Character counter for deskripsi
    $('#deskripsi').on('input', function() {
        var maxLength = 500;
        var currentLength = $(this).val().length;
        var remaining = maxLength - currentLength;
        
        if (remaining < 0) {
            $(this).val($(this).val().substring(0, maxLength));
        }
    });
});
</script>
@endpush
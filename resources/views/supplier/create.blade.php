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
                    <h5 class="m-b-10">Tambah Supplier</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('supplier.index') }}">Supplier</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Form Tambah Supplier</h5>
                        </div>
                        <div class="card-body">
                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Error!</strong> {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <strong>Terdapat kesalahan!</strong>
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif

                            <form action="{{ route('supplier.store') }}" method="POST" id="supplierForm">
                                @csrf
                                
                                <div class="row">
                                    <!-- Informasi Dasar -->
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <h6 class="text-primary border-bottom pb-2">
                                                <i class="feather-info me-2"></i>Informasi Dasar
                                            </h6>
                                        </div>
                                    </div>

                                    <!-- Nama Supplier -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Nama Supplier <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('nama_supplier') is-invalid @enderror" 
                                                   name="nama_supplier" 
                                                   value="{{ old('nama_supplier') }}" 
                                                   placeholder="Masukkan nama supplier"
                                                   required>
                                            @error('nama_supplier')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- No Telepon -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   class="form-control @error('no_telp') is-invalid @enderror" 
                                                   name="no_telp" 
                                                   value="{{ old('no_telp') }}" 
                                                   placeholder="Contoh: 08123456789"
                                                   required>
                                            @error('no_telp')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Email -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Email</label>
                                            <input type="email" 
                                                   class="form-control @error('email') is-invalid @enderror" 
                                                   name="email" 
                                                   value="{{ old('email') }}" 
                                                   placeholder="contoh@email.com">
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Kota -->
                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Kota</label>
                                            <input type="text" 
                                                   class="form-control @error('kota') is-invalid @enderror" 
                                                   name="kota" 
                                                   value="{{ old('kota') }}" 
                                                   placeholder="Masukkan nama kota">
                                            @error('kota')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Alamat Lengkap -->
                                    <div class="col-12">
                                        <div class="mb-4">
                                            <label class="form-label">Alamat Lengkap</label>
                                            <textarea class="form-control @error('alamat') is-invalid @enderror" 
                                                      name="alamat" 
                                                      rows="3" 
                                                      placeholder="Masukkan alamat lengkap">{{ old('alamat') }}</textarea>
                                            @error('alamat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Action Buttons -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <div class="d-flex justify-content-between">
                                            <a href="{{ route('supplier.index') }}" class="btn btn-light">
                                                <i class="feather-arrow-left me-2"></i>Kembali
                                            </a>
                                            <div class="d-flex gap-2">
                                                <button type="reset" class="btn btn-warning">
                                                    <i class="feather-refresh-cw me-2"></i>Reset
                                                </button>
                                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                                    <i class="feather-save me-2"></i>Simpan
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

@push('styles')
<style>
.form-label {
    font-weight: 500;
    margin-bottom: 0.5rem;
}

.form-control:focus {
    border-color: #4361ee;
    box-shadow: 0 0 0 0.2rem rgba(67, 97, 238, 0.25);
}

.text-primary {
    color: #4361ee !important;
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Form Validation
    $('#supplierForm').on('submit', function(e) {
        let isValid = true;
        
        // Validasi nama supplier
        if ($('input[name="nama_supplier"]').val().trim() === '') {
            isValid = false;
            alert('Nama supplier harus diisi!');
            return false;
        }
        
        // Validasi no telepon
        if ($('input[name="no_telp"]').val().trim() === '') {
            isValid = false;
            alert('No. telepon harus diisi!');
            return false;
        }
        
        // Validasi format email jika diisi
        let email = $('input[name="email"]').val().trim();
        if (email !== '') {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                isValid = false;
                alert('Format email tidak valid!');
                return false;
            }
        }

        if (!isValid) {
            e.preventDefault();
        } else {
            // Disable submit button to prevent double submission
            $('#submitBtn').prop('disabled', true).html('<i class="feather-loader me-2"></i>Menyimpan...');
        }
    });

    // Auto format phone number
    $('input[name="no_telp"]').on('keypress', function(e) {
        // Only allow numbers
        if (e.which < 48 || e.which > 57) {
            e.preventDefault();
        }
    });
});
</script>
@endpush
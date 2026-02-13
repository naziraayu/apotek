@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('obat.index') }}">Master Obat</a></li>
                    <li class="breadcrumb-item active">Edit Obat</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('obat.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Form Edit Obat</h5>
                            <p class="text-muted mb-0">Update informasi obat {{ $obat->nama_obat }}</p>
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

                            <form action="{{ route('obat.update', $obat->id) }}" method="POST" id="formObat">
                                @csrf
                                @method('PUT')

                                <!-- Informasi Dasar -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-3 fw-bold text-primary">
                                            <i class="feather-info me-2"></i>Informasi Dasar
                                        </h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
                                            <input type="text" 
                                                   name="nama_obat" 
                                                   class="form-control @error('nama_obat') is-invalid @enderror" 
                                                   placeholder="Contoh: Paracetamol 500mg"
                                                   value="{{ old('nama_obat', $obat->nama_obat) }}"
                                                   required>
                                            @error('nama_obat')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Kategori Obat <span class="text-danger">*</span></label>
                                            <select name="kategori_id" 
                                                    class="form-select @error('kategori_id') is-invalid @enderror"
                                                    required>
                                                <option value="">-- Pilih Kategori --</option>
                                                @foreach($kategoris as $kategori)
                                                    <option value="{{ $kategori->id }}" {{ old('kategori_id', $obat->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                                        {{ $kategori->nama_kategori }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('kategori_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-4">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="deskripsi" 
                                                      class="form-control @error('deskripsi') is-invalid @enderror" 
                                                      rows="3" 
                                                      placeholder="Deskripsi obat (opsional)">{{ old('deskripsi', $obat->deskripsi) }}</textarea>
                                            @error('deskripsi')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Informasi Harga & Stok -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-3 fw-bold text-primary">
                                            <i class="feather-dollar-sign me-2"></i>Harga & Stok
                                        </h6>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label class="form-label">Satuan <span class="text-danger">*</span></label>
                                            <select name="satuan" 
                                                    class="form-select @error('satuan') is-invalid @enderror"
                                                    required>
                                                <option value="">-- Pilih Satuan --</option>
                                                @php
                                                    $satuans = ['Strip', 'Box', 'Botol', 'Tube', 'Kaplet', 'Tablet', 'Kapsul', 'Sachet', 'Ampul', 'Vial'];
                                                @endphp
                                                @foreach($satuans as $satuan)
                                                    <option value="{{ $satuan }}" {{ old('satuan', $obat->satuan) == $satuan ? 'selected' : '' }}>
                                                        {{ $satuan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('satuan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label class="form-label">Harga Beli <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" 
                                                       name="harga_beli" 
                                                       class="form-control @error('harga_beli') is-invalid @enderror" 
                                                       placeholder="0"
                                                       value="{{ old('harga_beli', $obat->harga_beli) }}"
                                                       min="0"
                                                       step="0.01"
                                                       id="hargaBeli"
                                                       required>
                                                @error('harga_beli')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="mb-4">
                                            <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                            <div class="input-group">
                                                <span class="input-group-text">Rp</span>
                                                <input type="number" 
                                                       name="harga_jual" 
                                                       class="form-control @error('harga_jual') is-invalid @enderror" 
                                                       placeholder="0"
                                                       value="{{ old('harga_jual', $obat->harga_jual) }}"
                                                       min="0"
                                                       step="0.01"
                                                       id="hargaJual"
                                                       required>
                                                @error('harga_jual')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            <small class="text-muted" id="profitMargin"></small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Stok Saat Ini <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   name="stok" 
                                                   class="form-control @error('stok') is-invalid @enderror" 
                                                   placeholder="0"
                                                   value="{{ old('stok', $obat->stok) }}"
                                                   min="0"
                                                   required>
                                            @error('stok')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Stok Minimum <span class="text-danger">*</span></label>
                                            <input type="number" 
                                                   name="stok_minimum" 
                                                   class="form-control @error('stok_minimum') is-invalid @enderror" 
                                                   placeholder="10"
                                                   value="{{ old('stok_minimum', $obat->stok_minimum) }}"
                                                   min="0"
                                                   required>
                                            @error('stok_minimum')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Alert akan muncul jika stok mencapai nilai ini</small>
                                        </div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <!-- Informasi Batch & Kadaluarsa -->
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-3 fw-bold text-primary">
                                            <i class="feather-package me-2"></i>Informasi Batch & Kadaluarsa
                                        </h6>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Nomor Batch</label>
                                            <input type="text" 
                                                   name="no_batch" 
                                                   class="form-control @error('no_batch') is-invalid @enderror" 
                                                   placeholder="Contoh: BATCH2024001"
                                                   value="{{ old('no_batch', $obat->no_batch) }}"
                                                   id="noBatch">
                                            @error('no_batch')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Nomor batch dari supplier (opsional)</small>
                                            <div id="batchWarning" class="text-danger small mt-1" style="display: none;">
                                                Nomor batch sudah digunakan!
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-4">
                                            <label class="form-label">Tanggal Kadaluarsa</label>
                                            <input type="date" 
                                                   name="tanggal_kadaluarsa" 
                                                   class="form-control @error('tanggal_kadaluarsa') is-invalid @enderror" 
                                                   value="{{ old('tanggal_kadaluarsa', $obat->tanggal_kadaluarsa?->format('Y-m-d')) }}">
                                            @error('tanggal_kadaluarsa')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                            <small class="text-muted">Tanggal kadaluarsa obat (opsional)</small>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <div class="d-flex justify-content-end gap-2 mt-4">
                                    <a href="{{ route('obat.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Batal
                                    </a>
                                    <button type="reset" class="btn btn-warning">
                                        <i class="feather-refresh-cw me-2"></i>Reset
                                    </button>
                                    <button type="submit" class="btn btn-primary" id="btnSubmit">
                                        <i class="feather-save me-2"></i>Update Data
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@push('scripts')
<script>
// Auto-calculate profit margin
function calculateProfitMargin() {
    const hargaBeli = parseFloat(document.getElementById('hargaBeli').value) || 0;
    const hargaJual = parseFloat(document.getElementById('hargaJual').value) || 0;
    const profitMarginEl = document.getElementById('profitMargin');

    if (hargaBeli > 0 && hargaJual > 0) {
        const margin = ((hargaJual - hargaBeli) / hargaBeli) * 100;
        const profit = hargaJual - hargaBeli;
        
        if (hargaJual <= hargaBeli) {
            profitMarginEl.innerHTML = '<span class="text-danger"><i class="feather-alert-circle"></i> Harga jual harus lebih besar dari harga beli!</span>';
        } else {
            profitMarginEl.innerHTML = `<span class="text-success"><i class="feather-trending-up"></i> Profit: Rp ${profit.toLocaleString('id-ID')} (${margin.toFixed(2)}%)</span>`;
        }
    } else {
        profitMarginEl.innerHTML = '';
    }
}

document.getElementById('hargaBeli').addEventListener('input', calculateProfitMargin);
document.getElementById('hargaJual').addEventListener('input', calculateProfitMargin);

// Calculate on load
calculateProfitMargin();

// Check batch number availability
let batchCheckTimeout;
const originalBatch = '{{ $obat->no_batch }}';
document.getElementById('noBatch').addEventListener('input', function() {
    const noBatch = this.value;
    const warningEl = document.getElementById('batchWarning');
    
    if (noBatch.length < 3 || noBatch === originalBatch) {
        warningEl.style.display = 'none';
        return;
    }

    clearTimeout(batchCheckTimeout);
    batchCheckTimeout = setTimeout(() => {
        fetch(`{{ route('obat.checkKode') }}?kode=${encodeURIComponent(noBatch)}&id={{ $obat->id }}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    warningEl.style.display = 'block';
                } else {
                    warningEl.style.display = 'none';
                }
            })
            .catch(error => {
                console.error('Error checking batch:', error);
            });
    }, 500);
});

// Form validation
document.getElementById('formObat').addEventListener('submit', function(e) {
    const hargaBeli = parseFloat(document.getElementById('hargaBeli').value) || 0;
    const hargaJual = parseFloat(document.getElementById('hargaJual').value) || 0;

    if (hargaJual <= hargaBeli) {
        e.preventDefault();
        alert('Harga jual harus lebih besar dari harga beli!');
        document.getElementById('hargaJual').focus();
        return false;
    }

    const btnSubmit = document.getElementById('btnSubmit');
    btnSubmit.disabled = true;
    btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memperbarui...';
});

// Format number inputs
document.querySelectorAll('input[type="number"]').forEach(input => {
    input.addEventListener('wheel', function(e) {
        e.preventDefault();
    });
});
</script>
@endpush
@endsection
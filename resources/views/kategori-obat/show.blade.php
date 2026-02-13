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
                    <h5 class="m-b-10">Detail Kategori Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('kategori-obat.index') }}">Kategori Obat</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <a href="{{ route('kategori-obat.edit', $kategori->id) }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i>
                            <span>Edit</span>
                        </a>
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
                <!-- Detail Information -->
                <div class="col-lg-4">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Kategori</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <label class="form-label text-muted mb-1">Nama Kategori</label>
                                <h5 class="fw-bold text-dark">{{ $kategori->nama_kategori }}</h5>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted mb-1">Deskripsi</label>
                                <p class="fs-13 text-dark mb-0">
                                    {{ $kategori->deskripsi ?? '-' }}
                                </p>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted mb-1">Jumlah Obat</label>
                                <h4 class="fw-bold text-primary">{{ $kategori->obat->count() }} Obat</h4>
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-muted mb-1">Status</label>
                                <div>
                                    @if($kategori->obat->count() > 0)
                                        <span class="badge bg-soft-success text-success">Aktif</span>
                                    @else
                                        <span class="badge bg-soft-secondary text-secondary">Kosong</span>
                                    @endif
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="mb-3">
                                <label class="form-label text-muted mb-1">Dibuat Pada</label>
                                <p class="fs-13 mb-0">{{ $kategori->created_at->format('d M Y, H:i') }}</p>
                            </div>

                            <div>
                                <label class="form-label text-muted mb-1">Terakhir Diperbarui</label>
                                <p class="fs-13 mb-0">{{ $kategori->updated_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Statistics Card -->
                    <div class="card border-dashed border-primary bg-soft-primary">
                        <div class="card-body">
                            <div class="text-center">
                                <i class="feather-package fs-1 text-primary mb-3"></i>
                                <h3 class="fw-bold text-dark mb-1">{{ $kategori->obat->count() }}</h3>
                                <p class="text-muted mb-0">Total Obat Terdaftar</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- List of Obat -->
                <div class="col-lg-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Obat dalam Kategori</h5>
                            <div class="card-header-action">
                                @if($kategori->obat->count() > 0)
                                <span class="badge bg-soft-primary text-primary">
                                    {{ $kategori->obat->count() }} Obat
                                </span>
                                @endif
                            </div>
                        </div>
                        <div class="card-body p-0">
                            @if($kategori->obat->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Nama Obat</th>
                                                <th>Satuan</th>
                                                <th>Stok</th>
                                                <th>Harga Jual</th>
                                                <th>Status</th>
                                                <th class="text-end">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($kategori->obat as $index => $obat)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>
                                                        <div class="fw-bold text-dark">{{ $obat->nama_obat }}</div>
                                                        @if($obat->no_batch)
                                                            <small class="fs-12 text-muted">Batch: {{ $obat->no_batch }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $obat->satuan }}</td>
                                                    <td>
                                                        <span class="badge {{ $obat->stok == 0 ? 'bg-soft-danger text-danger' : ($obat->stok <= 10 ? 'bg-soft-warning text-warning' : 'bg-soft-success text-success') }}">
                                                            {{ $obat->stok }} {{ $obat->satuan }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $obat->harga_jual_format ?? 'Rp 0' }}</td>
                                                    <td>
                                                        @if($obat->stok == 0)
                                                            <span class="badge bg-soft-danger text-danger">Habis</span>
                                                        @elseif($obat->stok <= 10)
                                                            <span class="badge bg-soft-warning text-warning">Stok Minimum</span>
                                                        @else
                                                            <span class="badge bg-soft-success text-success">Normal</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <div class="hstack gap-2 justify-content-end">
                                                            <a href="{{ route('obat.show', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Detail">
                                                                <i class="feather-eye"></i>
                                                            </a>
                                                            <a href="{{ route('obat.edit', $obat->id) }}" class="avatar-text avatar-md" data-bs-toggle="tooltip" title="Edit">
                                                                <i class="feather-edit"></i>
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="feather-inbox fs-1 text-muted"></i>
                                    <p class="mt-3 text-muted">Belum ada obat dalam kategori ini</p>
                                    <a href="{{ route('obat.create') }}" class="btn btn-sm btn-primary mt-2">
                                        <i class="feather-plus me-2"></i>Tambah Obat
                                    </a>
                                </div>
                            @endif
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
/* Badge Styling */
.badge {
    padding: 0.35em 0.65em;
    font-size: 0.75rem;
    font-weight: 500;
}

/* Table Hover Effect */
.table-hover tbody tr:hover {
    background-color: rgba(0, 0, 0, 0.02);
}

/* Avatar Text Hover */
.avatar-text {
    transition: all 0.2s ease;
}

.avatar-text:hover {
    transform: translateY(-2px);
    background-color: rgba(0, 0, 0, 0.05);
}
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Initialize Tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush
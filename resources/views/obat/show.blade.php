@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Detail Obat</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('obat.index') }}">Master Obat</a></li>
                    <li class="breadcrumb-item active">Detail</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2">
                        <a href="{{ route('obat.index') }}" class="btn btn-light">
                            <i class="feather-arrow-left me-2"></i>
                            <span>Kembali</span>
                        </a>
                        <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-primary">
                            <i class="feather-edit me-2"></i>
                            <span>Edit Data</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                <!-- Info Card -->
                <div class="col-lg-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Informasi Obat</h5>
                        </div>
                        <div class="card-body">
                            <!-- Nama Obat -->
                            <div class="mb-4 pb-4 border-bottom">
                                <h4 class="fw-bold mb-2">{{ $obat->nama_obat }}</h4>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="badge bg-soft-primary text-primary">
                                        {{ $obat->kategori->nama_kategori ?? '-' }}
                                    </span>
                                    @if($obat->no_batch)
                                        <span class="badge bg-soft-secondary text-secondary">
                                            Batch: {{ $obat->no_batch }}
                                        </span>
                                    @endif
                                    {!! $obat->status_badge !!}
                                </div>
                            </div>

                            <!-- Informasi Dasar -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 text-primary">
                                    <i class="feather-info me-2"></i>Informasi Dasar
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">Kategori</label>
                                        <p class="fw-semibold mb-0">{{ $obat->kategori->nama_kategori ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Satuan</label>
                                        <p class="fw-semibold mb-0">{{ $obat->satuan }}</p>
                                    </div>
                                    @if($obat->deskripsi)
                                        <div class="col-12">
                                            <label class="text-muted small">Deskripsi</label>
                                            <p class="mb-0">{{ $obat->deskripsi }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informasi Harga -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 text-primary">
                                    <i class="feather-dollar-sign me-2"></i>Informasi Harga
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="text-muted small">Harga Beli</label>
                                        <p class="fw-semibold mb-0 fs-5 text-danger">{{ $obat->harga_beli_format }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Harga Jual</label>
                                        <p class="fw-semibold mb-0 fs-5 text-success">{{ $obat->harga_jual_format }}</p>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="text-muted small">Profit Margin</label>
                                        <p class="fw-semibold mb-0 fs-5 text-primary">{{ $obat->profit_margin_format }}</p>
                                        <small class="text-muted">
                                            Profit: {{ number_format($obat->harga_jual - $obat->harga_beli, 0, ',', '.') }}
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- Informasi Stok -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3 text-primary">
                                    <i class="feather-package me-2"></i>Informasi Stok
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">Stok Saat Ini</label>
                                        <p class="fw-semibold mb-0">
                                            <span class="badge {{ $obat->stok == 0 ? 'bg-danger' : ($obat->isStokMinimum() ? 'bg-warning' : 'bg-success') }} fs-5">
                                                {{ $obat->stok }} {{ $obat->satuan }}
                                            </span>
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Stok Minimum</label>
                                        <p class="fw-semibold mb-0">{{ $obat->stok_minimum }} {{ $obat->satuan }}</p>
                                    </div>
                                    @if($obat->isStokMinimum())
                                        <div class="col-12">
                                            <div class="alert alert-warning mb-0">
                                                <i class="feather-alert-triangle me-2"></i>
                                                Stok mencapai batas minimum! Segera lakukan pemesanan ulang.
                                            </div>
                                        </div>
                                    @endif
                                    @if($obat->stok == 0)
                                        <div class="col-12">
                                            <div class="alert alert-danger mb-0">
                                                <i class="feather-alert-circle me-2"></i>
                                                Stok habis! Obat tidak tersedia untuk dijual.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Informasi Batch & Kadaluarsa -->
                            <div class="mb-0">
                                <h6 class="fw-bold mb-3 text-primary">
                                    <i class="feather-calendar me-2"></i>Batch & Kadaluarsa
                                </h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="text-muted small">Nomor Batch</label>
                                        <p class="fw-semibold mb-0">{{ $obat->no_batch ?? '-' }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="text-muted small">Tanggal Kadaluarsa</label>
                                        <p class="fw-semibold mb-0">
                                            {{ $obat->tanggal_kadaluarsa ? $obat->tanggal_kadaluarsa->format('d M Y') : '-' }}
                                        </p>
                                        @if($obat->tanggal_kadaluarsa && $obat->days_until_expiry !== null)
                                            <small class="text-muted">
                                                {{ $obat->days_until_expiry }} hari lagi
                                            </small>
                                        @endif
                                    </div>
                                    @if($obat->isNearExpired())
                                        <div class="col-12">
                                            <div class="alert alert-info mb-0">
                                                <i class="feather-info me-2"></i>
                                                Obat akan kadaluarsa dalam {{ $obat->days_until_expiry }} hari!
                                            </div>
                                        </div>
                                    @endif
                                    @if($obat->isExpired())
                                        <div class="col-12">
                                            <div class="alert alert-dark mb-0">
                                                <i class="feather-x-circle me-2"></i>
                                                Obat sudah kadaluarsa! Tidak boleh dijual.
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stats & Action Card -->
                <div class="col-lg-4">
                    <!-- Quick Stats -->
                    <div class="card stretch stretch-full mb-3">
                        <div class="card-header">
                            <h5 class="card-title">Statistik Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Status Stok</span>
                                    @if($obat->stok == 0)
                                        <span class="badge bg-danger">Habis</span>
                                    @elseif($obat->isStokMinimum())
                                        <span class="badge bg-warning">Minimum</span>
                                    @else
                                        <span class="badge bg-success">Aman</span>
                                    @endif
                                </div>
                                <div class="progress" style="height: 8px;">
                                    @php
                                        $percentage = $obat->stok_minimum > 0 ? min(($obat->stok / ($obat->stok_minimum * 5)) * 100, 100) : 100;
                                        $colorClass = $obat->stok == 0 ? 'bg-danger' : ($obat->isStokMinimum() ? 'bg-warning' : 'bg-success');
                                    @endphp
                                    <div class="progress-bar {{ $colorClass }}" style="width: {{ $percentage }}%"></div>
                                </div>
                                <small class="text-muted">{{ $obat->stok }} / {{ $obat->stok_minimum * 5 }} (optimal)</small>
                            </div>

                            <div class="mb-3">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="text-muted small">Profit Margin</span>
                                    <span class="badge bg-primary">{{ number_format($obat->profit_margin, 1) }}%</span>
                                </div>
                                <div class="progress" style="height: 8px;">
                                    <div class="progress-bar bg-primary" style="width: {{ min($obat->profit_margin, 100) }}%"></div>
                                </div>
                            </div>

                            @if($obat->tanggal_kadaluarsa)
                                <div class="mb-0">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-muted small">Masa Simpan</span>
                                        @if($obat->isExpired())
                                            <span class="badge bg-dark">Kadaluarsa</span>
                                        @elseif($obat->isNearExpired())
                                            <span class="badge bg-info">{{ $obat->days_until_expiry }} hari</span>
                                        @else
                                            <span class="badge bg-success">Aman</span>
                                        @endif
                                    </div>
                                    @if(!$obat->isExpired())
                                        @php
                                            $daysPercentage = min(($obat->days_until_expiry / 365) * 100, 100);
                                            $expiryColorClass = $obat->isNearExpired() ? 'bg-info' : 'bg-success';
                                        @endphp
                                        <div class="progress" style="height: 8px;">
                                            <div class="progress-bar {{ $expiryColorClass }}" style="width: {{ $daysPercentage }}%"></div>
                                        </div>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Aksi Cepat</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-grid gap-2">
                                <a href="{{ route('obat.edit', $obat->id) }}" class="btn btn-primary">
                                    <i class="feather-edit me-2"></i>Edit Data Obat
                                </a>
                                <button type="button" class="btn btn-danger" onclick="deleteObat()">
                                    <i class="feather-trash-2 me-2"></i>Hapus Obat
                                </button>
                            </div>

                            <hr class="my-3">

                            <div class="small text-muted">
                                <div class="d-flex justify-content-between mb-2">
                                    <span>Dibuat:</span>
                                    <span class="fw-semibold">{{ $obat->created_at->format('d M Y H:i') }}</span>
                                </div>
                                <div class="d-flex justify-content-between">
                                    <span>Diupdate:</span>
                                    <span class="fw-semibold">{{ $obat->updated_at->format('d M Y H:i') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Delete Form (Hidden) -->
<form id="deleteForm" action="{{ route('obat.destroy', $obat->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@push('scripts')
<script>
function deleteObat() {
    if (confirm('Yakin ingin menghapus obat "{{ $obat->nama_obat }}"?\n\nData yang dihapus tidak dapat dikembalikan!')) {
        document.getElementById('deleteForm').submit();
    }
}
</script>
@endpush
@endsection
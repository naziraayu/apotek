@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Transaksi Penjualan Baru</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('penjualan.index') }}">Penjualan</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <a href="{{ route('penjualan.index') }}" class="btn btn-light">
                    <i class="feather-arrow-left me-2"></i>Kembali
                </a>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <form action="{{ route('penjualan.store') }}" method="POST" id="formPenjualan">
                @csrf
                <div class="row">
                    <!-- Left Side - Form Input -->
                    <div class="col-lg-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Detail Transaksi</h5>
                            </div>
                            <div class="card-body">
                                @if(session('error'))
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <strong>Error!</strong> {{ session('error') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                    </div>
                                @endif

                                <!-- Informasi Transaksi -->
                                <div class="row mb-3">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">No Nota <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" value="{{ $noNota }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Tanggal <span class="text-danger">*</span></label>
                                            <input type="date" name="tanggal_penjualan" class="form-control @error('tanggal_penjualan') is-invalid @enderror" value="{{ old('tanggal_penjualan', date('Y-m-d')) }}" required>
                                            @error('tanggal_penjualan')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Pelanggan -->
                                <div class="mb-3">
                                    <label class="form-label">Pelanggan</label>
                                    <select name="pelanggan_id" class="form-select select2" id="pelangganSelect">
                                        <option value="">-- Pilih Pelanggan (Opsional) --</option>
                                        @foreach($pelanggans as $pelanggan)
                                            <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                                                {{ $pelanggan->nama_pelanggan }} - {{ $pelanggan->no_telepon }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Kosongkan jika pelanggan umum</small>
                                </div>

                                <hr class="my-4">

                                <!-- Item Obat -->
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <label class="form-label mb-0">Daftar Obat <span class="text-danger">*</span></label>
                                        <button type="button" class="btn btn-sm btn-primary" id="btnTambahItem">
                                            <i class="feather-plus me-1"></i>Tambah Obat
                                        </button>
                                    </div>

                                    <div class="table-responsive">
                                        <table class="table table-bordered" id="tableObat">
                                            <thead class="bg-light">
                                                <tr>
                                                    <th width="35%">Obat</th>
                                                    <th width="15%">Harga</th>
                                                    <th width="15%">Stok</th>
                                                    <th width="15%">Jumlah</th>
                                                    <th width="15%">Subtotal</th>
                                                    <th width="5%">Aksi</th>
                                                </tr>
                                            </thead>
                                            <tbody id="itemContainer">
                                                <!-- Items akan ditambahkan di sini -->
                                            </tbody>
                                        </table>
                                    </div>
                                    @error('obat_id')
                                        <div class="text-danger small mt-1">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Right Side - Summary -->
                    <div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Ringkasan Pembayaran</h5>
                            </div>
                            <div class="card-body">
                                <!-- Total Harga -->
                                <div class="d-flex justify-content-between mb-3">
                                    <span class="text-muted">Total Harga:</span>
                                    <span class="fw-bold" id="displayTotalHarga">Rp 0</span>
                                </div>

                                <!-- Diskon -->
                                <div class="mb-3">
                                    <label class="form-label">Diskon</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="diskon" class="form-control" id="inputDiskon" value="{{ old('diskon', 0) }}" min="0" step="1000">
                                    </div>
                                </div>

                                <hr>

                                <!-- Grand Total -->
                                <div class="d-flex justify-content-between mb-4">
                                    <h5 class="mb-0">Grand Total:</h5>
                                    <h5 class="mb-0 text-primary" id="displayGrandTotal">Rp 0</h5>
                                </div>

                                <hr>

                                <!-- Metode Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label">Metode Pembayaran <span class="text-danger">*</span></label>
                                    <select name="metode_pembayaran" class="form-select @error('metode_pembayaran') is-invalid @enderror" required>
                                        <option value="">-- Pilih Metode --</option>
                                        <option value="tunai" {{ old('metode_pembayaran') == 'tunai' ? 'selected' : '' }}>Tunai</option>
                                        <option value="transfer" {{ old('metode_pembayaran') == 'transfer' ? 'selected' : '' }}>Transfer</option>
                                        <option value="kartu_kredit" {{ old('metode_pembayaran') == 'kartu_kredit' ? 'selected' : '' }}>Kartu Kredit</option>
                                        <option value="e-wallet" {{ old('metode_pembayaran') == 'e-wallet' ? 'selected' : '' }}>E-Wallet</option>
                                    </select>
                                    @error('metode_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Status Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label">Status Pembayaran <span class="text-danger">*</span></label>
                                    <select name="status_pembayaran" class="form-select @error('status_pembayaran') is-invalid @enderror" required>
                                        <option value="">-- Pilih Status --</option>
                                        <option value="lunas" {{ old('status_pembayaran') == 'lunas' ? 'selected' : '' }}>Lunas</option>
                                        <option value="belum_lunas" {{ old('status_pembayaran') == 'belum_lunas' ? 'selected' : '' }}>Belum Lunas</option>
                                    </select>
                                    @error('status_pembayaran')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <hr>

                                <!-- Action Buttons -->
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="feather-save me-2"></i>Simpan Transaksi
                                    </button>
                                    <a href="{{ route('penjualan.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Batal
                                    </a>
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
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
<style>
.table-bordered th,
.table-bordered td {
    vertical-align: middle;
}

.select2-container {
    width: 100% !important;
}

.item-row {
    animation: fadeIn 0.3s;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    let itemCount = 0;
    const obatData = @json($obats);

    // Initialize Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        placeholder: '-- Pilih --',
        allowClear: true
    });

    // Tambah Item
    $('#btnTambahItem').click(function() {
        addItemRow();
    });

    // Function untuk menambah baris item
    function addItemRow() {
        itemCount++;
        
        const row = `
            <tr class="item-row" id="row-${itemCount}">
                <td>
                    <select name="obat_id[]" class="form-select form-select-sm select-obat" data-row="${itemCount}" required>
                        <option value="">-- Pilih Obat --</option>
                        ${obatData.map(obat => `
                            <option value="${obat.id}" 
                                data-harga="${obat.harga_jual}" 
                                data-stok="${obat.stok}"
                                data-satuan="${obat.satuan}">
                                ${obat.nama_obat} (${obat.kode_obat})
                            </option>
                        `).join('')}
                    </select>
                </td>
                <td>
                    <input type="number" name="harga_satuan[]" class="form-control form-control-sm harga-input" id="harga-${itemCount}" readonly required>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <span class="badge bg-soft-info text-info" id="stok-${itemCount}">0</span>
                        <small class="ms-1 text-muted" id="satuan-${itemCount}"></small>
                    </div>
                </td>
                <td>
                    <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" id="jumlah-${itemCount}" min="1" value="1" required data-row="${itemCount}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm subtotal-display" id="subtotal-${itemCount}" readonly>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger btn-remove" data-row="${itemCount}">
                        <i class="feather-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;
        
        $('#itemContainer').append(row);
    }

    // Event: Pilih Obat
    $(document).on('change', '.select-obat', function() {
        const row = $(this).data('row');
        const selected = $(this).find(':selected');
        const harga = selected.data('harga');
        const stok = selected.data('stok');
        const satuan = selected.data('satuan');

        $(`#harga-${row}`).val(harga);
        $(`#stok-${row}`).text(stok);
        $(`#satuan-${row}`).text(satuan);
        $(`#jumlah-${row}`).attr('max', stok);

        calculateSubtotal(row);
    });

    // Event: Ubah Jumlah
    $(document).on('input', '.jumlah-input', function() {
        const row = $(this).data('row');
        const max = parseInt($(this).attr('max'));
        const value = parseInt($(this).val());

        if (value > max) {
            alert(`Stok tidak mencukupi! Maksimal: ${max}`);
            $(this).val(max);
        }

        calculateSubtotal(row);
    });

    // Event: Ubah Diskon
    $('#inputDiskon').on('input', function() {
        calculateGrandTotal();
    });

    // Event: Hapus Item
    $(document).on('click', '.btn-remove', function() {
        const row = $(this).data('row');
        $(`#row-${row}`).remove();
        calculateGrandTotal();
    });

    // Calculate Subtotal per item
    function calculateSubtotal(row) {
        const harga = parseFloat($(`#harga-${row}`).val()) || 0;
        const jumlah = parseInt($(`#jumlah-${row}`).val()) || 0;
        const subtotal = harga * jumlah;

        $(`#subtotal-${row}`).val(formatRupiah(subtotal));
        calculateGrandTotal();
    }

    // Calculate Grand Total
    function calculateGrandTotal() {
        let totalHarga = 0;

        $('.jumlah-input').each(function() {
            const row = $(this).data('row');
            const harga = parseFloat($(`#harga-${row}`).val()) || 0;
            const jumlah = parseInt($(this).val()) || 0;
            totalHarga += (harga * jumlah);
        });

        const diskon = parseFloat($('#inputDiskon').val()) || 0;
        const grandTotal = totalHarga - diskon;

        $('#displayTotalHarga').text(formatRupiah(totalHarga));
        $('#displayGrandTotal').text(formatRupiah(grandTotal));
    }

    // Format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Validasi Form
    $('#formPenjualan').submit(function(e) {
        if ($('#itemContainer tr').length === 0) {
            e.preventDefault();
            alert('Minimal harus ada 1 obat!');
            return false;
        }
    });

    // Auto add 1 row on load
    addItemRow();
});
</script>
@endpush
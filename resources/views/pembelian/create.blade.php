@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- [ page-header ] start -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tambah Pembelian</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('pembelian.index') }}">Pembelian</a></li>
                    <li class="breadcrumb-item active">Tambah</li>
                </ul>
            </div>
        </div>
        <!-- [ page-header ] end -->

        <!-- [ Main Content ] start -->
        <div class="main-content">
            <form action="{{ route('pembelian.store') }}" method="POST" id="formPembelian">
                @csrf
                <div class="row">
                    <!-- Form Input -->
                    <div class="col-lg-8">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Informasi Pembelian</h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">No Nota <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" value="{{ $noNota }}" readonly>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Tanggal Pembelian <span class="text-danger">*</span></label>
                                        <input type="date" name="tanggal_pembelian" class="form-control @error('tanggal_pembelian') is-invalid @enderror" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required>
                                        @error('tanggal_pembelian')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Supplier <span class="text-danger">*</span></label>
                                        <select name="supplier_id" id="supplier_id" class="form-select @error('supplier_id') is-invalid @enderror" required>
                                            <option value="">Pilih Supplier</option>
                                            @foreach($suppliers as $supplier)
                                                <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                                    {{ $supplier->nama_supplier }} - {{ $supplier->no_telp }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('supplier_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">Keterangan</label>
                                        <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan') }}</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="col-lg-4">
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Ringkasan</h5>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">Total Harga</label>
                                    <input type="text" class="form-control" id="displayTotal" value="Rp 0" readonly>
                                    <input type="hidden" id="totalHarga" value="0">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Diskon</label>
                                    <input type="number" name="diskon" id="diskon" class="form-control @error('diskon') is-invalid @enderror" value="{{ old('diskon', 0) }}" min="0" step="0.01">
                                    @error('diskon')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h5 class="mb-0">Grand Total</h5>
                                        <h4 class="mb-0 text-primary" id="grandTotal">Rp 0</h4>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="feather-save me-2"></i>Simpan Pembelian
                                    </button>
                                    <a href="{{ route('pembelian.index') }}" class="btn btn-light">
                                        <i class="feather-x me-2"></i>Batal
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- Detail Pembelian -->
                        <div class="card stretch stretch-full">
                            <div class="card-header">
                                <h5 class="card-title">Detail Pembelian</h5>
                                <button type="button" class="btn btn-sm btn-primary" id="addItem">
                                    <i class="feather-plus me-1"></i> Tambah Item
                                </button>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-bordered mb-0" id="itemTable">
                                        <thead class="bg-light">
                                            <tr>
                                                <th width="30%">Obat</th>
                                                <th width="10%">Jumlah</th>
                                                <th width="15%">Harga Beli</th>
                                                <th width="15%">Subtotal</th>
                                                <th width="13%">Exp Date</th>
                                                <th width="12%">No Batch</th>
                                                <th width="5%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="itemList">
                                            <!-- Items will be added here dynamically -->
                                        </tbody>
                                    </table>
                                </div>
                                <div class="p-3 text-center text-muted" id="emptyState">
                                    <i class="feather-package fs-1"></i>
                                    <p class="mt-2">Belum ada item. Klik tombol "Tambah Item" untuk menambahkan.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <!-- Info -->
                        <div class="card stretch stretch-full">
                            <div class="card-body">
                                <h6 class="mb-3"><i class="feather-info text-primary me-2"></i>Informasi</h6>
                                <ul class="list-unstyled fs-13 text-muted">
                                    <li class="mb-2"><i class="feather-check-circle text-success me-2"></i>Pastikan data supplier sudah benar</li>
                                    <li class="mb-2"><i class="feather-check-circle text-success me-2"></i>Periksa harga beli setiap item</li>
                                    <li class="mb-2"><i class="feather-check-circle text-success me-2"></i>Input tanggal kadaluarsa jika ada</li>
                                    <li class="mb-2"><i class="feather-check-circle text-success me-2"></i>No batch opsional</li>
                                </ul>
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

@push('scripts')
<script>
$(document).ready(function() {
    let itemIndex = 0;
    let obatData = @json($obat);

    // Add Item
    $('#addItem').on('click', function() {
        addItemRow();
    });

    function addItemRow() {
        itemIndex++;
        
        let obatOptions = '<option value="">Pilih Obat</option>';
        obatData.forEach(function(item) {
            obatOptions += `<option value="${item.id}" data-harga="${item.harga_beli}" data-satuan="${item.satuan}">${item.nama_obat} - ${item.kode_obat} (Stok: ${item.stok})</option>`;
        });

        let row = `
            <tr class="item-row" data-index="${itemIndex}">
                <td>
                    <select name="obat_id[]" class="form-select form-select-sm obat-select" required>
                        ${obatOptions}
                    </select>
                </td>
                <td>
                    <input type="number" name="jumlah[]" class="form-control form-control-sm jumlah-input" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" name="harga_beli[]" class="form-control form-control-sm harga-input" value="0" min="0" step="0.01" required>
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm subtotal-display" value="Rp 0" readonly>
                </td>
                <td>
                    <input type="date" name="tanggal_kadaluarsa[]" class="form-control form-control-sm">
                </td>
                <td>
                    <input type="text" name="no_batch[]" class="form-control form-control-sm" placeholder="Batch">
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger remove-item">
                        <i class="feather-trash-2"></i>
                    </button>
                </td>
            </tr>
        `;

        $('#itemList').append(row);
        $('#emptyState').hide();
        calculateTotal();
    }

    // Remove Item
    $(document).on('click', '.remove-item', function() {
        $(this).closest('tr').remove();
        if ($('#itemList tr').length === 0) {
            $('#emptyState').show();
        }
        calculateTotal();
    });

    // Obat Select Change
    $(document).on('change', '.obat-select', function() {
        let selectedOption = $(this).find('option:selected');
        let hargaBeli = selectedOption.data('harga');
        let row = $(this).closest('tr');
        
        row.find('.harga-input').val(hargaBeli);
        calculateRowSubtotal(row);
    });

    // Jumlah or Harga Change
    $(document).on('input', '.jumlah-input, .harga-input', function() {
        let row = $(this).closest('tr');
        calculateRowSubtotal(row);
    });

    // Diskon Change
    $('#diskon').on('input', function() {
        calculateTotal();
    });

    // Calculate Row Subtotal
    function calculateRowSubtotal(row) {
        let jumlah = parseFloat(row.find('.jumlah-input').val()) || 0;
        let harga = parseFloat(row.find('.harga-input').val()) || 0;
        let subtotal = jumlah * harga;
        
        row.find('.subtotal-display').val(formatRupiah(subtotal));
        calculateTotal();
    }

    // Calculate Total
    function calculateTotal() {
        let total = 0;
        $('.item-row').each(function() {
            let jumlah = parseFloat($(this).find('.jumlah-input').val()) || 0;
            let harga = parseFloat($(this).find('.harga-input').val()) || 0;
            total += (jumlah * harga);
        });

        let diskon = parseFloat($('#diskon').val()) || 0;
        let grandTotal = total - diskon;

        $('#totalHarga').val(total);
        $('#displayTotal').val(formatRupiah(total));
        $('#grandTotal').text(formatRupiah(grandTotal));
    }

    // Format Rupiah
    function formatRupiah(angka) {
        return 'Rp ' + angka.toFixed(0).replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    }

    // Form Validation
    $('#formPembelian').on('submit', function(e) {
        if ($('#itemList tr').length === 0) {
            e.preventDefault();
            alert('Minimal harus ada 1 item pembelian!');
            return false;
        }

        // Validate each item
        let valid = true;
        $('.item-row').each(function() {
            let obat = $(this).find('.obat-select').val();
            let jumlah = $(this).find('.jumlah-input').val();
            let harga = $(this).find('.harga-input').val();
            
            if (!obat || !jumlah || jumlah <= 0 || !harga || harga < 0) {
                valid = false;
                return false;
            }
        });

        if (!valid) {
            e.preventDefault();
            alert('Pastikan semua item sudah diisi dengan benar!');
            return false;
        }
    });

    // Add first item on load
    addItemRow();
});
</script>
@endpush
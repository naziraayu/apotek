<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Penjualan - {{ $penjualan->no_nota }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            padding: 20px;
        }

        .nota-container {
            max-width: 800px;
            margin: 0 auto;
            border: 1px solid #ddd;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
            color: #2c3e50;
        }

        .header p {
            font-size: 11px;
            color: #666;
            margin: 2px 0;
        }

        .info-section {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .info-left,
        .info-right {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .info-item {
            margin-bottom: 5px;
        }

        .info-item label {
            font-weight: bold;
            display: inline-block;
            width: 120px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .table td {
            font-size: 11px;
        }

        .table .text-right {
            text-align: right;
        }

        .table .text-center {
            text-align: center;
        }

        .summary-section {
            float: right;
            width: 300px;
            margin-bottom: 20px;
        }

        .summary-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
            padding: 5px 0;
        }

        .summary-item.total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
            padding-top: 10px;
            margin-top: 10px;
        }

        .footer {
            clear: both;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px dashed #999;
        }

        .signature-section {
            display: table;
            width: 100%;
            margin-top: 50px;
        }

        .signature-box {
            display: table-cell;
            width: 33%;
            text-align: center;
        }

        .signature-line {
            margin-top: 60px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .notes {
            margin-top: 20px;
            font-size: 10px;
            color: #666;
        }

        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }

        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }

        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }

        .badge-info {
            background-color: #d1ecf1;
            color: #0c5460;
        }
    </style>
</head>
<body>
    <div class="nota-container">
        <!-- Header -->
        <div class="header">
            <h1>APOTEK [NAMA APOTEK]</h1>
            <p>Jl. Alamat Apotek No. 123, Kota</p>
            <p>Telp: (021) 12345678 | Email: apotek@email.com</p>
            <p>APOTEKER: [Nama Apoteker], S.Farm., Apt | SIPA: [Nomor SIPA]</p>
        </div>

        <!-- Informasi Nota -->
        <div class="info-section">
            <div class="info-left">
                <div class="info-item">
                    <label>No Nota:</label>
                    <span>{{ $penjualan->no_nota }}</span>
                </div>
                <div class="info-item">
                    <label>Tanggal:</label>
                    <span>{{ $penjualan->tanggal_penjualan->format('d F Y, H:i') }}</span>
                </div>
                <div class="info-item">
                    <label>Kasir:</label>
                    <span>{{ $penjualan->kasir_nama }}</span>
                </div>
            </div>
            <div class="info-right">
                <div class="info-item">
                    <label>Pelanggan:</label>
                    <span>{{ $penjualan->pelanggan_nama }}</span>
                </div>
                <div class="info-item">
                    <label>Pembayaran:</label>
                    <span>{{ ucfirst(str_replace('_', ' ', $penjualan->metode_pembayaran)) }}</span>
                </div>
                <div class="info-item">
                    <label>Status:</label>
                    @if($penjualan->status_pembayaran === 'lunas')
                        <span class="badge badge-success">LUNAS</span>
                    @else
                        <span class="badge badge-warning">BELUM LUNAS</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tabel Item -->
        <table class="table">
            <thead>
                <tr>
                    <th width="5%" class="text-center">No</th>
                    <th width="40%">Nama Obat</th>
                    <th width="15%" class="text-right">Harga</th>
                    <th width="10%" class="text-center">Qty</th>
                    <th width="15%" class="text-right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($penjualan->details as $index => $detail)
                    <tr>
                        <td class="text-center">{{ $index + 1 }}</td>
                        <td>
                            <strong>{{ $detail->obat_nama }}</strong><br>
                            <small style="color: #666;">{{ $detail->obat->kode_obat ?? '' }}</small>
                        </td>
                        <td class="text-right">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                        <td class="text-center">{{ $detail->jumlah }} {{ $detail->obat->satuan ?? '' }}</td>
                        <td class="text-right">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Summary -->
        <div class="summary-section">
            <div class="summary-item">
                <span>Total Item:</span>
                <strong>{{ $penjualan->total_item }} item</strong>
            </div>
            <div class="summary-item">
                <span>Subtotal:</span>
                <strong>Rp {{ number_format($penjualan->total_harga, 0, ',', '.') }}</strong>
            </div>
            @if($penjualan->diskon > 0)
                <div class="summary-item" style="color: #28a745;">
                    <span>Diskon:</span>
                    <strong>- Rp {{ number_format($penjualan->diskon, 0, ',', '.') }}</strong>
                </div>
            @endif
            <div class="summary-item total">
                <span>GRAND TOTAL:</span>
                <strong>Rp {{ number_format($penjualan->grand_total, 0, ',', '.') }}</strong>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <!-- Tanda Tangan -->
            <div class="signature-section">
                <div class="signature-box">
                    <p>Kasir</p>
                    <div class="signature-line">
                        {{ $penjualan->kasir_nama }}
                    </div>
                </div>
                <div class="signature-box">
                    <p>Apoteker</p>
                    <div class="signature-line">
                        [Nama Apoteker]
                    </div>
                </div>
                <div class="signature-box">
                    <p>Pelanggan</p>
                    <div class="signature-line">
                        {{ $penjualan->pelanggan_nama }}
                    </div>
                </div>
            </div>

            <!-- Catatan -->
            <div class="notes">
                <p><strong>Catatan:</strong></p>
                <ul style="margin-left: 20px;">
                    <li>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan kecuali ada kesepakatan</li>
                    <li>Simpan nota ini sebagai bukti pembayaran yang sah</li>
                    <li>Untuk obat resep, gunakan sesuai anjuran dokter</li>
                    <li>Mohon periksa kembali obat yang dibeli sebelum meninggalkan apotek</li>
                </ul>
                <p style="margin-top: 10px; text-align: center;">
                    <strong>*** TERIMA KASIH ATAS KUNJUNGAN ANDA ***</strong>
                </p>
            </div>
        </div>
    </div>

    <script>
        // Auto print when loaded
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
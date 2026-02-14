<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 10px;
            color: #333;
            padding: 15px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 18px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 10px;
            color: #666;
            margin: 2px 0;
        }

        .info-period {
            background-color: #f5f5f5;
            padding: 10px;
            margin-bottom: 15px;
            border-left: 3px solid #007bff;
        }

        .statistics {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
        }

        .stat-label {
            font-size: 9px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 14px;
            font-weight: bold;
            color: #333;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .table th,
        .table td {
            border: 1px solid #ddd;
            padding: 6px;
            font-size: 9px;
        }

        .table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: left;
        }

        .table tfoot {
            background-color: #f9f9f9;
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #28a745;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 8px;
            color: #666;
        }

        .page-break {
            page-break-after: always;
        }

        @media print {
            body {
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p>APOTEK [NAMA APOTEK]</p>
        <p>Jl. Alamat Apotek No. 123, Kota | Telp: (021) 12345678</p>
    </div>

    <!-- Periode -->
    <div class="info-period">
        <strong>Periode:</strong> 
        @if(request('tanggal_mulai') && request('tanggal_akhir'))
            {{ date('d F Y', strtotime(request('tanggal_mulai'))) }} s/d {{ date('d F Y', strtotime(request('tanggal_akhir'))) }}
        @elseif(request('bulan') && request('tahun'))
            {{ DateTime::createFromFormat('!m', request('bulan'))->format('F') }} {{ request('tahun') }}
        @else
            Semua Data
        @endif
        | 
        <strong>Dicetak:</strong> {{ date('d F Y, H:i') }}
    </div>

    <!-- Statistik -->
    <div class="statistics">
        <div class="stat-box">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Penjualan</div>
            <div class="stat-value" style="font-size: 12px;">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Diskon</div>
            <div class="stat-value" style="font-size: 12px;">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Profit</div>
            <div class="stat-value text-success" style="font-size: 12px;">Rp {{ number_format($totalProfit, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Tabel -->
    <table class="table">
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">No Nota</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Pelanggan</th>
                <th width="10%">Kasir</th>
                <th width="7%">Item</th>
                <th width="12%">Total</th>
                <th width="10%">Diskon</th>
                <th width="12%">Grand Total</th>
                <th width="12%">Profit</th>
                <th width="7%">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($penjualans as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->no_nota }}</td>
                    <td>{{ $item->tanggal_penjualan->format('d/m/Y H:i') }}</td>
                    <td>{{ $item->pelanggan_nama }}</td>
                    <td>{{ $item->kasir_nama }}</td>
                    <td class="text-center">{{ $item->total_item }}</td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right text-success">Rp {{ number_format($item->diskon, 0, ',', '.') }}</td>
                    <td class="text-right"><strong>Rp {{ number_format($item->grand_total, 0, ',', '.') }}</strong></td>
                    <td class="text-right text-success"><strong>Rp {{ number_format($item->total_profit, 0, ',', '.') }}</strong></td>
                    <td class="text-center">{{ $item->status_pembayaran === 'lunas' ? 'LUNAS' : 'BELUM' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        @if($penjualans->count() > 0)
            <tfoot>
                <tr>
                    <td colspan="6" class="text-right"><strong>TOTAL:</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($penjualans->sum('total_harga'), 0, ',', '.') }}</strong></td>
                    <td class="text-right text-success"><strong>Rp {{ number_format($penjualans->sum('diskon'), 0, ',', '.') }}</strong></td>
                    <td class="text-right"><strong>Rp {{ number_format($penjualans->sum('grand_total'), 0, ',', '.') }}</strong></td>
                    <td class="text-right text-success"><strong>Rp {{ number_format($totalProfit, 0, ',', '.') }}</strong></td>
                    <td></td>
                </tr>
            </tfoot>
        @endif
    </table>

    <!-- Summary -->
    @if($penjualans->count() > 0)
        <div style="margin-top: 20px; padding: 10px; background-color: #f9f9f9; border: 1px solid #ddd;">
            <h3 style="font-size: 12px; margin-bottom: 10px;">RINGKASAN:</h3>
            <table style="width: 100%; font-size: 10px;">
                <tr>
                    <td style="width: 70%;"><strong>Total Transaksi:</strong></td>
                    <td style="text-align: right;"><strong>{{ $totalTransaksi }} transaksi</strong></td>
                </tr>
                <tr>
                    <td><strong>Rata-rata per Transaksi:</strong></td>
                    <td style="text-align: right;"><strong>Rp {{ number_format($totalPenjualan / $totalTransaksi, 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td><strong>Margin Profit:</strong></td>
                    <td style="text-align: right;" class="text-success"><strong>{{ number_format(($totalProfit / $totalPenjualan) * 100, 2) }}%</strong></td>
                </tr>
            </table>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dicetak pada {{ date('d F Y, H:i:s') }}</p>
        <p>APOTEK [NAMA APOTEK] - Sistem Manajemen Apotek</p>
    </div>
</body>
</html>
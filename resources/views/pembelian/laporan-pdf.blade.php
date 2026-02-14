<!DOCTYPE html>
<html>
<head>
    <title>Laporan Pembelian</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 11px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 20px;
            background-color: #f5f5f5;
            padding: 10px;
        }
        .info-row {
            margin-bottom: 5px;
        }
        .stats-row {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        .stat-box {
            display: table-cell;
            width: 25%;
            padding: 10px;
            text-align: center;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .stat-label {
            font-size: 10px;
            color: #666;
            margin-bottom: 5px;
        }
        .stat-value {
            font-size: 14px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #333;
            color: white;
            padding: 8px;
            text-align: left;
            border: 1px solid #333;
            font-size: 10px;
        }
        table td {
            padding: 6px;
            border: 1px solid #ddd;
        }
        table tfoot th {
            background-color: #f0f0f0;
            color: #333;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        .badge-selesai {
            background-color: #28a745;
            color: #fff;
        }
        .badge-batal {
            background-color: #dc3545;
            color: #fff;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>LAPORAN PEMBELIAN</h2>
        <p>APOTEK [NAMA APOTEK]</p>
        <p style="font-size: 10px;">Periode: 
            @if(request('tanggal_mulai') && request('tanggal_akhir'))
                {{ \Carbon\Carbon::parse(request('tanggal_mulai'))->format('d/m/Y') }} - {{ \Carbon\Carbon::parse(request('tanggal_akhir'))->format('d/m/Y') }}
            @else
                Semua Periode
            @endif
        </p>
    </div>

    @if(request('supplier_id') || request('status'))
        <div class="info-section">
            @if(request('supplier_id'))
                <div class="info-row">
                    <strong>Supplier:</strong> {{ $pembelian->first()->supplier_nama ?? '-' }}
                </div>
            @endif
            @if(request('status'))
                <div class="info-row">
                    <strong>Status:</strong> {{ strtoupper(request('status')) }}
                </div>
            @endif
        </div>
    @endif

    <div class="stats-row">
        <div class="stat-box">
            <div class="stat-label">Total Transaksi</div>
            <div class="stat-value">{{ $totalTransaksi }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Pembelian</div>
            <div class="stat-value">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Total Diskon</div>
            <div class="stat-value">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</div>
        </div>
        <div class="stat-box">
            <div class="stat-label">Grand Total</div>
            <div class="stat-value">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="12%">Tanggal</th>
                <th width="15%">No Nota</th>
                <th width="25%">Supplier</th>
                <th width="10%" class="text-center">Status</th>
                <th width="13%" class="text-right">Total Harga</th>
                <th width="10%" class="text-right">Diskon</th>
                <th width="13%" class="text-right">Grand Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($pembelian as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->tanggal_pembelian->format('d/m/Y') }}</td>
                    <td>{{ $item->no_nota }}</td>
                    <td>{{ $item->supplier_nama }}</td>
                    <td class="text-center">
                        <span class="badge badge-{{ $item->status }}">{{ strtoupper($item->status) }}</span>
                    </td>
                    <td class="text-right">Rp {{ number_format($item->total_harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->diskon, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->grand_total, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center" style="padding: 30px;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if($pembelian->count() > 0)
            <tfoot>
                <tr>
                    <th colspan="5" class="text-right">TOTAL:</th>
                    <th class="text-right">Rp {{ number_format($totalPembelian, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($totalDiskon, 0, ',', '.') }}</th>
                    <th class="text-right">Rp {{ number_format($grandTotal, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d F Y H:i:s') }}</p>
        <p>Laporan ini dibuat secara otomatis oleh sistem</p>
    </div>
</body>
</html>
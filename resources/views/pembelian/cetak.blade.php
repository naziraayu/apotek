<!DOCTYPE html>
<html>
<head>
    <title>Pembelian {{ $pembelian->no_nota }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h2 {
            margin-bottom: 5px;
        }
        .info-section {
            margin-bottom: 20px;
        }
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 5px;
        }
        .info-label {
            display: table-cell;
            width: 150px;
            font-weight: bold;
        }
        .info-value {
            display: table-cell;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th {
            background-color: #f0f0f0;
            padding: 8px;
            text-align: left;
            border: 1px solid #ddd;
        }
        table td {
            padding: 8px;
            border: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .total-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .total-row {
            display: table;
            width: 100%;
            margin-bottom: 8px;
        }
        .total-label {
            display: table-cell;
            font-weight: bold;
        }
        .total-value {
            display: table-cell;
            text-align: right;
        }
        .grand-total {
            border-top: 2px solid #333;
            padding-top: 8px;
            font-size: 14px;
        }
        .footer {
            margin-top: 50px;
            clear: both;
        }
        .signature {
            display: inline-block;
            width: 200px;
            text-align: center;
            margin: 50px 30px 0;
        }
        .signature-line {
            border-top: 1px solid #000;
            margin-top: 60px;
            padding-top: 5px;
        }
        .badge {
            display: inline-block;
            padding: 3px 8px;
            border-radius: 3px;
            font-size: 10px;
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
    </style>
</head>
<body>
    <div class="header">
        <h2>NOTA PEMBELIAN</h2>
        <p>APOTEK [NAMA APOTEK]</p>
        <p>Alamat Apotek | Telp: (0123) 456789</p>
    </div>

    <div class="info-section">
        <div class="info-row">
            <div class="info-label">No Nota</div>
            <div class="info-value">: {{ $pembelian->no_nota }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Tanggal</div>
            <div class="info-value">: {{ $pembelian->tanggal_pembelian->format('d F Y') }}</div>
        </div>
        <div class="info-row">
            <div class="info-label">Supplier</div>
            <div class="info-value">: {{ $pembelian->supplier_nama }}</div>
        </div>
        @if($pembelian->supplier->no_telp)
            <div class="info-row">
                <div class="info-label">Telepon Supplier</div>
                <div class="info-value">: {{ $pembelian->supplier->no_telp }}</div>
            </div>
        @endif
        @if($pembelian->supplier->alamat)
            <div class="info-row">
                <div class="info-label">Alamat Supplier</div>
                <div class="info-value">: {{ $pembelian->supplier->alamat }}</div>
            </div>
        @endif
        <div class="info-row">
            <div class="info-label">Status</div>
            <div class="info-value">
                : <span class="badge badge-{{ $pembelian->status }}">{{ strtoupper($pembelian->status) }}</span>
            </div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%" class="text-center">No</th>
                <th width="30%">Nama Obat</th>
                <th width="15%">Kode</th>
                <th width="10%" class="text-center">Jumlah</th>
                <th width="15%" class="text-right">Harga Beli</th>
                <th width="15%" class="text-right">Subtotal</th>
                <th width="10%" class="text-center">Exp</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pembelian->details as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->nama_obat }}</td>
                    <td>{{ $detail->kode_obat }}</td>
                    <td class="text-center">{{ $detail->jumlah }} {{ $detail->satuan }}</td>
                    <td class="text-right">{{ $detail->harga_beli_format }}</td>
                    <td class="text-right">{{ $detail->subtotal_format }}</td>
                    <td class="text-center">
                        @if($detail->tanggal_kadaluarsa)
                            {{ $detail->tanggal_kadaluarsa->format('m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <div class="total-label">Total Harga:</div>
            <div class="total-value">{{ $pembelian->total_harga_format }}</div>
        </div>
        <div class="total-row">
            <div class="total-label">Diskon:</div>
            <div class="total-value">{{ $pembelian->diskon_format }}</div>
        </div>
        <div class="total-row grand-total">
            <div class="total-label">GRAND TOTAL:</div>
            <div class="total-value">{{ $pembelian->grand_total_format }}</div>
        </div>
    </div>

    <div class="footer">
        @if($pembelian->keterangan)
            <p style="margin-bottom: 20px;"><strong>Keterangan:</strong> {{ $pembelian->keterangan }}</p>
        @endif

        <div class="signature">
            <p>Penerima,</p>
            <div class="signature-line">
                ( ............................. )
            </div>
        </div>

        <div class="signature">
            <p>Hormat kami,</p>
            <div class="signature-line">
                ( {{ $pembelian->user_nama }} )
            </div>
        </div>
    </div>

    <p style="margin-top: 30px; text-align: center; font-size: 10px; color: #666;">
        Dicetak pada: {{ now()->format('d F Y H:i:s') }}
    </p>
</body>
</html>
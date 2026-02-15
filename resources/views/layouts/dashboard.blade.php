@extends('layouts.template')

@section('content')
<main class="nxl-container">
    <div class="nxl-content">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-header-left d-flex align-items-center">
                <div class="page-header-title">
                    <h5 class="m-b-10">Dashboard Apotek</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item">Dashboard</li>
                </ul>
            </div>
            <div class="page-header-right ms-auto">
                <div class="page-header-right-items">
                    <div class="d-flex align-items-center gap-2 page-header-right-items-wrapper">
                        <div id="reportrange" class="reportrange-picker d-flex align-items-center">
                            <span class="reportrange-picker-field"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="row">
                <!-- KARTU STATISTIK ATAS -->
                <!-- Penjualan Hari Ini -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-success">
                                        <i class="feather-dollar-sign"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">
                                            <span>Rp {{ number_format($penjualanHariIni, 0, ',', '.') }}</span>
                                        </div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Penjualan Hari Ini</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('penjualan.index') }}" class="fs-12 fw-medium text-muted">
                                        Total Transaksi: {{ $transaksiHariIni }}
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Stok Minimum -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-warning">
                                        <i class="feather-alert-triangle"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">
                                            <span>{{ $obatStokMinimum }}</span>
                                        </div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Obat Stok Minimum</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('obat.stok') }}" class="fs-12 fw-medium text-muted">
                                        Perlu Restock Segera
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Akan Kadaluarsa -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-danger">
                                        <i class="feather-calendar"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">
                                            <span>{{ $obatAkanKadaluarsa }}</span>
                                        </div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Akan Kadaluarsa</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('obat.kadaluarsa') }}" class="fs-12 fw-medium text-muted">
                                        Dalam 90 Hari
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pending Approval -->
                <div class="col-xxl-3 col-md-6">
                    <div class="card stretch stretch-full">
                        <div class="card-body">
                            <div class="d-flex align-items-start justify-content-between mb-4">
                                <div class="d-flex gap-4 align-items-center">
                                    <div class="avatar-text avatar-lg bg-primary">
                                        <i class="feather-clock"></i>
                                    </div>
                                    <div>
                                        <div class="fs-4 fw-bold text-dark">
                                            <span>{{ $pendingApproval }}</span>
                                        </div>
                                        <h3 class="fs-13 fw-semibold text-truncate-1-line">Menunggu Verifikasi</h3>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <div class="d-flex align-items-center justify-content-between">
                                    <a href="{{ route('penjualan.index') }}?status_pembayaran=pending" class="fs-12 fw-medium text-muted">
                                        Perlu Approval
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- CHART PENJUALAN 7 HARI -->
                <div class="col-xxl-8">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Penjualan 7 Hari Terakhir</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="penjualan7HariChart" height="100"></canvas>
                        </div>
                    </div>
                </div>

                <!-- RINGKASAN FINANSIAL -->
                <div class="col-xxl-4">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Ringkasan Finansial</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Total Nilai Stok</span>
                                    <span class="fs-14 fw-bold text-dark">Rp {{ number_format($totalNilaiStok, 0, ',', '.') }}</span>
                                </div>
                                <div class="progress ht-5">
                                    <div class="progress-bar bg-primary" style="width: 100%"></div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fs-12 text-muted">Profit Bulan Ini</span>
                                    <span class="fs-14 fw-bold text-success">Rp {{ number_format($profitBulanIni, 0, ',', '.') }}</span>
                                </div>
                                <div class="progress ht-5">
                                    <div class="progress-bar bg-success" style="width: 85%"></div>
                                </div>
                            </div>

                            <hr class="border-dashed my-3">

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <i class="feather-users text-primary"></i>
                                    <span class="fs-12 ms-2">Total Pelanggan</span>
                                </div>
                                <span class="fs-14 fw-bold">{{ $totalPelanggan }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div>
                                    <i class="feather-user-plus text-success"></i>
                                    <span class="fs-12 ms-2">Pelanggan Baru</span>
                                </div>
                                <span class="fs-14 fw-bold">{{ $pelangganBaru }}</span>
                            </div>

                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <i class="feather-star text-warning"></i>
                                    <span class="fs-12 ms-2">Pelanggan VIP</span>
                                </div>
                                <span class="fs-14 fw-bold">{{ $pelangganVIP }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- TOP 5 OBAT TERLARIS -->
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Top 5 Obat Terlaris</h5>
                        </div>
                        <div class="card-body">
                            @forelse($obatTerlaris as $obat)
                                <div class="mb-4">
                                    <div class="d-flex justify-content-between mb-2">
                                        <div>
                                            <div class="fw-semibold text-dark">{{ $obat->nama_obat }}</div>
                                            <div class="fs-11 text-muted">{{ $obat->kategori->nama_kategori ?? '-' }}</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-bold text-success">{{ $obat->total_terjual }} {{ $obat->satuan }}</div>
                                            <div class="fs-11 text-muted">Rp {{ number_format($obat->total_pendapatan, 0, ',', '.') }}</div>
                                        </div>
                                    </div>
                                    <div class="progress ht-3">
                                        <div class="progress-bar bg-success" style="width: {{ ($obat->total_terjual / $obatTerlaris->first()->total_terjual) * 100 }}%"></div>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted text-center">Belum ada data penjualan</p>
                            @endforelse
                        </div>
                        <a href="{{ route('obat.index') }}" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">Lihat Semua Obat</a>
                    </div>
                </div>

                <!-- OBAT STOK KRITIS -->
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Obat Stok Kritis</h5>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th>Nama Obat</th>
                                            <th>Stok</th>
                                            <th>Min</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obatStokKritis as $obat)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $obat->nama_obat }}</div>
                                                    <div class="fs-11 text-muted">{{ $obat->kategori->nama_kategori ?? '-' }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $obat->stok == 0 ? 'bg-danger' : 'bg-warning text-dark' }}">
                                                        {{ $obat->stok }} {{ $obat->satuan }}
                                                    </span>
                                                </td>
                                                <td>{{ $obat->stok_minimum }} {{ $obat->satuan }}</td>
                                                <td>
                                                    @if($obat->stok == 0)
                                                        <span class="badge bg-danger">Habis</span>
                                                    @else
                                                        <span class="badge bg-warning text-dark">Kritis</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Semua obat stok aman</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ route('obat.stok') }}" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">Lihat Detail Stok</a>
                    </div>
                </div>

                <!-- OBAT AKAN KADALUARSA -->
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Obat Akan Kadaluarsa</h5>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th>Nama Obat</th>
                                            <th>Batch</th>
                                            <th>Tanggal</th>
                                            <th>Sisa Hari</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obatKadaluarsa as $obat)
                                            <tr>
                                                <td>
                                                    <div class="fw-semibold">{{ $obat->nama_obat }}</div>
                                                    <div class="fs-11 text-muted">Stok: {{ $obat->stok }} {{ $obat->satuan }}</div>
                                                </td>
                                                <td>{{ $obat->no_batch ?? '-' }}</td>
                                                <td>{{ $obat->tanggal_kadaluarsa->format('d/m/Y') }}</td>
                                                <td>
                                                    @php
                                                        $sisaHari = now()->diffInDays($obat->tanggal_kadaluarsa, false);
                                                    @endphp
                                                    <span class="badge {{ $sisaHari <= 30 ? 'bg-danger' : ($sisaHari <= 60 ? 'bg-warning text-dark' : 'bg-info') }}">
                                                        {{ $sisaHari }} hari
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Tidak ada obat yang akan kadaluarsa</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ route('obat.kadaluarsa') }}" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">Lihat Semua</a>
                    </div>
                </div>

                <!-- TRANSAKSI TERBARU -->
                <div class="col-xxl-6">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Transaksi Terbaru</h5>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th>No Nota</th>
                                            <th>Pelanggan</th>
                                            <th>Total</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($transaksiTerbaru as $transaksi)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('penjualan.show', $transaksi->id) }}" class="fw-semibold text-primary">
                                                        {{ $transaksi->no_nota }}
                                                    </a>
                                                    <div class="fs-11 text-muted">{{ $transaksi->tanggal_penjualan->format('d/m/Y H:i') }}</div>
                                                </td>
                                                <td>{{ $transaksi->pelanggan_nama }}</td>
                                                <td class="fw-bold">Rp {{ number_format($transaksi->grand_total, 0, ',', '.') }}</td>
                                                <td>{!! $transaksi->status_pembayaran_badge !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center text-muted">Belum ada transaksi</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <a href="{{ route('penjualan.index') }}" class="card-footer fs-11 fw-bold text-uppercase text-center py-4">Lihat Semua Transaksi</a>
                    </div>
                </div>

                <!-- DAFTAR OBAT TERJUAL HARI INI -->
                <div class="col-12">
                    <div class="card stretch stretch-full">
                        <div class="card-header">
                            <h5 class="card-title">Daftar Obat Terjual Hari Ini</h5>
                            <div class="card-header-action">
                                <span class="badge bg-primary">{{ $obatTerjualHariIni->count() }} Jenis Obat</span>
                            </div>
                        </div>
                        <div class="card-body custom-card-action p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr class="border-b">
                                            <th>No</th>
                                            <th>Nama Obat</th>
                                            <th>Kategori</th>
                                            <th class="text-center">Jumlah Terjual</th>
                                            <th class="text-center">Transaksi</th>
                                            <th class="text-end">Total Pendapatan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($obatTerjualHariIni as $index => $obat)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>
                                                    <div class="fw-semibold text-dark">{{ $obat->nama_obat }}</div>
                                                </td>
                                                <td>
                                                    <span class="badge bg-soft-primary text-primary">{{ $obat->nama_kategori }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-success">{{ $obat->total_terjual }} {{ $obat->satuan }}</span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge bg-info">{{ $obat->jumlah_transaksi }}x</span>
                                                </td>
                                                <td class="text-end">
                                                    <span class="fw-bold text-success">Rp {{ number_format($obat->total_pendapatan, 0, ',', '.') }}</span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center text-muted py-4">
                                                    <i class="feather-shopping-bag fs-3 mb-2 d-block"></i>
                                                    Belum ada obat yang terjual hari ini
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                    @if($obatTerjualHariIni->count() > 0)
                                        <tfoot class="bg-light">
                                            <tr>
                                                <td colspan="3" class="fw-bold">TOTAL</td>
                                                <td class="text-center fw-bold">
                                                    {{ $obatTerjualHariIni->sum('total_terjual') }} Item
                                                </td>
                                                <td class="text-center fw-bold">
                                                    {{ $obatTerjualHariIni->sum('jumlah_transaksi') }}x
                                                </td>
                                                <td class="text-end fw-bold text-success">
                                                    Rp {{ number_format($obatTerjualHariIni->sum('total_pendapatan'), 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        </tfoot>
                                    @endif
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart Penjualan 7 Hari
    const ctx = document.getElementById('penjualan7HariChart').getContext('2d');
    const penjualan7HariChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($penjualan7Hari->pluck('tanggal')->map(function($date) {
                return \Carbon\Carbon::parse($date)->format('d M');
            })) !!},
            datasets: [{
                label: 'Penjualan (Rp)',
                data: {!! json_encode($penjualan7Hari->pluck('total')) !!},
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
</script>
@endpush
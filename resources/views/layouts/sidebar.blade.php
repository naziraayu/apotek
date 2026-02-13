@php
    $user = auth()->user();
@endphp
<!--! ================================================================ !-->
<!--! [Start] Navigation Manu !-->
<!--! ================================================================ !-->
<nav class="nxl-navigation">
    <div class="navbar-wrapper">
        <div class="m-header">
            <a href="{{ route('dashboard') }}" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="assets/images/LOGO.png" alt="" class="logo logo-lg" style="width: 220px"/>
                <img src="assets/images/logo_kecil.png" alt="" class="logo logo-sm" />
            </a>
        </div>
        <div class="navbar-content">
            <ul class="nxl-navbar">
                <li class="nxl-item nxl-caption">
                    <label>Navigation</label>
                </li>
                
                {{-- Dashboard --}}
                <li class="nxl-item {{ Request::is('dashboard') ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class="nxl-link">
                        <span class="nxl-micon"><i class="feather-airplay"></i></span>
                        <span class="nxl-mtext">Dashboard</span>
                    </a>
                </li>

                <li class="nxl-item nxl-caption">
                    <label>Menu</label>
                </li>

                {{-- Penjualan --}}
                @if($user->hasPermission('detail', 'penjualan'))
                    <li class="nxl-item nxl-hasmenu {{ Request::is('penjualan*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-shopping-cart"></i></span>
                            <span class="nxl-mtext">Penjualan</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            @if($user->hasPermission('detail', 'penjualan'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('penjualan.index') }}">Daftar Penjualan</a></li>
                            @endif
                            @if($user->hasPermission('add', 'penjualan'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('penjualan.create') }}">Transaksi Penjualan</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'penjualan'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('penjualan.laporan') }}">Laporan Penjualan</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Pembelian --}}
                @if($user->hasPermission('detail', 'pembelian'))
                    <li class="nxl-item nxl-hasmenu {{ Request::is('pembelian*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-truck"></i></span>
                            <span class="nxl-mtext">Pembelian</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            @if($user->hasPermission('detail', 'pembelian'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('pembelian.index') }}">Daftar Pembelian</a></li>
                            @endif
                            @if($user->hasPermission('add', 'pembelian'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('pembelian.create') }}">Transaksi Pembelian</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'pembelian'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('pembelian.laporan') }}">Laporan Pembelian</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Obat --}}
                @if($user->hasPermission('detail', 'obat'))
                    <li class="nxl-item nxl-hasmenu {{ Request::is('obat*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-package"></i></span>
                            <span class="nxl-mtext">Obat</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            @if($user->hasPermission('detail', 'obat'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('obat.index') }}">Daftar Obat</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'obat'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('obat.stok') }}">Stok Obat</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'obat'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('obat.kadaluarsa') }}">Obat Kadaluarsa</a></li>
                            @endif
                        </ul>
                    </li>
                @endif

                {{-- Kategori Obat --}}
                @if($user->hasPermission('detail', 'kategori_obat'))
                    <li class="nxl-item {{ Request::is('kategori-obat*') ? 'active' : '' }}">
                        <a href="{{ route('kategori-obat.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-tag"></i></span>
                            <span class="nxl-mtext">Kategori Obat</span>
                        </a>
                    </li>
                @endif

                {{-- Supplier --}}
                @if($user->hasPermission('detail', 'supplier'))
                    <li class="nxl-item {{ Request::is('supplier*') ? 'active' : '' }}">
                        <a href="{{ route('supplier.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-truck"></i></span>
                            <span class="nxl-mtext">Supplier</span>
                        </a>
                    </li>
                @endif

                {{-- Pelanggan --}}
                @if($user->hasPermission('detail', 'pelanggan'))
                    <li class="nxl-item {{ Request::is('pelanggan*') ? 'active' : '' }}">
                        <a href="{{ route('pelanggan.index') }}" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-users"></i></span>
                            <span class="nxl-mtext">Pelanggan</span>
                        </a>
                    </li>
                @endif

                {{-- Laporan --}}
                @if($user->features()->intersect(['penjualan','pembelian','obat'])->isNotEmpty())
                    <li class="nxl-item nxl-caption">
                        <label>Laporan</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ Request::is('laporan*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-bar-chart-2"></i></span>
                            <span class="nxl-mtext">Laporan</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            @if($user->hasPermission('detail', 'penjualan'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('laporan.penjualan') }}">Laporan Penjualan</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'pembelian'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('laporan.pembelian') }}">Laporan Pembelian</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'obat'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('laporan.stok') }}">Laporan Stok</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'obat'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('laporan.kadaluarsa') }}">Laporan Kadaluarsa</a></li>
                            @endif
                            <li class="nxl-item"><a class="nxl-link" href="{{ route('laporan.keuangan') }}">Laporan Keuangan</a></li>
                        </ul>
                    </li>
                @endif

                {{-- Pengaturan --}}
                @if($user->features()->intersect(['hak_akses','user','profile'])->isNotEmpty())
                    <li class="nxl-item nxl-caption">
                        <label>Pengaturan</label>
                    </li>
                    <li class="nxl-item nxl-hasmenu {{ Request::is('roles*') || Request::is('users*') || Request::is('profile*') ? 'active' : '' }}">
                        <a href="javascript:void(0);" class="nxl-link">
                            <span class="nxl-micon"><i class="feather-settings"></i></span>
                            <span class="nxl-mtext">Pengaturan</span>
                            <span class="nxl-arrow"><i class="feather-chevron-right"></i></span>
                        </a>
                        <ul class="nxl-submenu">
                            @if($user->hasPermission('detail', 'hak_akses'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('roles.index') }}">Hak Akses</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'user'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('users.index') }}">User</a></li>
                            @endif
                            @if($user->hasPermission('detail', 'profile'))
                                <li class="nxl-item"><a class="nxl-link" href="{{ route('profile.edit') }}">Profil</a></li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>

            {{-- Logout Card --}}
            <div class="card text-center">
                <div class="card-body">
                    <i class="feather-power fs-4 text-dark"></i>
                    <h6 class="mt-4 text-dark fw-bolder">Sistem Apoteker</h6>
                    <p class="fs-11 my-3 text-dark">Kelola sistem apotek Anda dengan mudah dan efisien.</p>
                    <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                        @csrf
                    </form>
                    <a href="#" class="btn btn-primary text-dark w-100" onclick="event.preventDefault(); if(confirm('Apakah Anda yakin ingin keluar?')) document.getElementById('logout-form').submit();">
                        <i class="feather-log-out me-2"></i>Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
</nav>
<!--! ================================================================ !-->
<!--! [End]  Navigation Manu !-->
<!--! ================================================================ !-->
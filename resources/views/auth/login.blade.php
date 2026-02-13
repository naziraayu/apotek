<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keyword" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - Login</title>
    
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/images/favicon.ico') }}">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.min.css') }}">
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/vendors/css/vendors.min.css') }}">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/theme.min.css') }}">
</head>

<body>
    <!-- Main Content -->
    <main class="auth-cover-wrapper">
        <div class="auth-cover-content-inner">
            <div class="auth-cover-content-wrapper">
                <div class="auth-img">
                    <img src="{{ asset('assets/images/auth/auth-cover-login-bg.svg') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                        <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">Login</h2>
                    <h4 class="fs-13 fw-bold mb-2">Login ke akun Anda</h4>
                    <p class="fs-12 fw-medium text-muted">Selamat datang kembali di aplikasi kami, silakan akses akun terbaik Anda.</p>
                    
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="alert alert-success mb-4" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login') }}" class="w-100 mt-4 pt-2">
                        @csrf

                        <!-- Email Address -->
                        <div class="mb-4">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                placeholder="Email atau Username" 
                                value="{{ old('email') }}" 
                                required 
                                autofocus 
                                autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-3">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                placeholder="Password" 
                                required 
                                autocomplete="current-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Remember Me & Forgot Password -->
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="custom-control custom-checkbox">
                                    <input 
                                        type="checkbox" 
                                        class="custom-control-input" 
                                        id="rememberMe" 
                                        name="remember"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="custom-control-label c-pointer" for="rememberMe">Ingat Saya</label>
                                </div>
                            </div>
                            <div>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="fs-11 text-primary">Lupa password?</a>
                                @endif
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Login</button>
                        </div>
                    </form>

                    <!-- Social Login (Optional - bisa dihapus jika tidak digunakan) -->
                    {{-- <div class="w-100 mt-5 text-center mx-auto">
                        <div class="mb-4 border-bottom position-relative">
                            <span class="small py-1 px-3 text-uppercase text-muted bg-white position-absolute translate-middle">atau</span>
                        </div>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login dengan Facebook">
                                <i class="feather-facebook"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login dengan Twitter">
                                <i class="feather-twitter"></i>
                            </a>
                            <a href="javascript:void(0);" class="btn btn-light-brand flex-fill" data-bs-toggle="tooltip" data-bs-trigger="hover" title="Login dengan Github">
                                <i class="feather-github text"></i>
                            </a>
                        </div>
                    </div> --}}

                    <!-- Register Link (Optional - sesuaikan dengan kebutuhan) -->
                    @if (Route::has('register'))
                        <div class="mt-5 text-muted">
                            <span>Belum punya akun?</span>
                            <a href="{{ route('register') }}" class="fw-bold">Buat Akun Baru</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </main>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    
    <!-- Apps Init -->
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    
    <!-- Theme Customizer (Optional - bisa dihapus jika tidak diperlukan) -->
    {{-- <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script> --}}
</body>

</html>
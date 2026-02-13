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
    
    <title>{{ config('app.name', 'Laravel') }} - Register</title>
    
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
                    <img src="{{ asset('assets/images/auth/auth-cover-register-bg.svg') }}" alt="" class="img-fluid">
                </div>
            </div>
        </div>
        <div class="auth-cover-sidebar-inner">
            <div class="auth-cover-card-wrapper">
                <div class="auth-cover-card p-sm-5">
                    <div class="wd-50 mb-5">
                        <img src="{{ asset('assets/images/logo-abbr.png') }}" alt="" class="img-fluid">
                    </div>
                    <h2 class="fs-20 fw-bolder mb-4">Register</h2>
                    <h4 class="fs-13 fw-bold mb-2">Buat Akun Baru Anda</h4>
                    <p class="fs-12 fw-medium text-muted">Mari kita siapkan akun Anda, sehingga Anda dapat memverifikasi akun pribadi Anda dan mulai mengatur profil Anda.</p>
                    
                    <!-- Register Form -->
                    <form method="POST" action="{{ route('register') }}" class="w-100 mt-4 pt-2">
                        @csrf

                        <!-- Name -->
                        <div class="mb-4">
                            <input 
                                type="text" 
                                class="form-control @error('name') is-invalid @enderror" 
                                name="name" 
                                placeholder="Nama Lengkap" 
                                value="{{ old('name') }}" 
                                required 
                                autofocus 
                                autocomplete="name">
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Email Address -->
                        <div class="mb-4">
                            <input 
                                type="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                name="email" 
                                placeholder="Email" 
                                value="{{ old('email') }}" 
                                required 
                                autocomplete="username">
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div class="mb-4">
                            <input 
                                type="password" 
                                class="form-control @error('password') is-invalid @enderror" 
                                name="password" 
                                placeholder="Password" 
                                required 
                                autocomplete="new-password">
                            @error('password')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="mb-4">
                            <input 
                                type="password" 
                                class="form-control @error('password_confirmation') is-invalid @enderror" 
                                name="password_confirmation" 
                                placeholder="Konfirmasi Password" 
                                required 
                                autocomplete="new-password">
                            @error('password_confirmation')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <!-- Terms & Conditions (Optional - sesuaikan dengan kebutuhan) -->
                        {{-- <div class="mt-4">
                            <div class="custom-control custom-checkbox mb-2">
                                <input type="checkbox" class="custom-control-input" id="receiveMail" name="receive_mail">
                                <label class="custom-control-label c-pointer text-muted" for="receiveMail" style="font-weight: 400 !important">Ya, saya ingin menerima email komunitas</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input @error('terms') is-invalid @enderror" id="termsCondition" name="terms" required>
                                <label class="custom-control-label c-pointer text-muted" for="termsCondition" style="font-weight: 400 !important">
                                    Saya setuju dengan semua <a href="{{ route('terms') }}">Syarat &amp; Ketentuan</a> dan <a href="{{ route('policy') }}">Kebijakan</a>.
                                </label>
                                @error('terms')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div> --}}

                        <!-- Submit Button -->
                        <div class="mt-5">
                            <button type="submit" class="btn btn-lg btn-primary w-100">Buat Akun</button>
                        </div>
                    </form>

                    <!-- Login Link -->
                    <div class="mt-5 text-muted">
                        <span>Sudah punya akun?</span>
                        <a href="{{ route('login') }}" class="fw-bold">Login</a>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendors/js/vendors.min.js') }}"></script>
    
    <!-- Password Strength JS (Optional - untuk fitur password strength indicator) -->
    {{-- <script src="{{ asset('assets/vendors/js/lslstrength.min.js') }}"></script> --}}
    
    <!-- Apps Init -->
    <script src="{{ asset('assets/js/common-init.min.js') }}"></script>
    
    <!-- Theme Customizer (Optional - bisa dihapus jika tidak diperlukan) -->
    {{-- <script src="{{ asset('assets/js/theme-customizer-init.min.js') }}"></script> --}}
</body>

</html>
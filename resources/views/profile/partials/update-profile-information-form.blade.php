<div class="card-header">
    <h5 class="card-title">Profile Information</h5>
    <p class="text-muted mb-0">Update your account's profile information and email address.</p>
</div>
<div class="card-body">
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="row g-4">
            <!-- Name Field -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="name" class="form-label fw-bold">
                        Name <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        class="form-control @error('name') is-invalid @enderror" 
                        value="{{ old('name', $user->name) }}" 
                        required 
                        autofocus 
                        autocomplete="name"
                    >
                    @error('name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email Field -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="email" class="form-label fw-bold">
                        Email <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-control @error('email') is-invalid @enderror" 
                        value="{{ old('email', $user->email) }}" 
                        required 
                        autocomplete="username"
                    >
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Email Verification Notice -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="col-12">
                    <div class="alert alert-warning d-flex align-items-center" role="alert">
                        <i class="feather-alert-circle me-2"></i>
                        <div>
                            <strong>Your email address is unverified.</strong>
                            <button 
                                form="send-verification" 
                                type="submit"
                                class="btn btn-link p-0 ms-1 text-decoration-underline"
                            >
                                Click here to re-send the verification email.
                            </button>
                        </div>
                    </div>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success d-flex align-items-center" role="alert">
                            <i class="feather-check-circle me-2"></i>
                            <div>A new verification link has been sent to your email address.</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Submit Button -->
        <div class="d-flex align-items-center gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="feather-save me-2"></i>Save Changes
            </button>

            @if (session('status') === 'profile-updated')
                <span 
                    class="text-success d-flex align-items-center" 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >
                    <i class="feather-check-circle me-1"></i>
                    Saved successfully!
                </span>
            @endif
        </div>
    </form>
</div>
<div class="card-header">
    <h5 class="card-title">Update Password</h5>
    <p class="text-muted mb-0">Ensure your account is using a long, random password to stay secure.</p>
</div>
<div class="card-body">
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="row g-4">
            <!-- Current Password -->
            <div class="col-md-12">
                <div class="mb-3">
                    <label for="update_password_current_password" class="form-label fw-bold">
                        Current Password <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="update_password_current_password" 
                        name="current_password" 
                        class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                        autocomplete="current-password"
                        required
                    >
                    @error('current_password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- New Password -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="update_password_password" class="form-label fw-bold">
                        New Password <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="update_password_password" 
                        name="password" 
                        class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                        autocomplete="new-password"
                        required
                    >
                    @error('password', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="update_password_password_confirmation" class="form-label fw-bold">
                        Confirm Password <span class="text-danger">*</span>
                    </label>
                    <input 
                        type="password" 
                        id="update_password_password_confirmation" 
                        name="password_confirmation" 
                        class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                        autocomplete="new-password"
                        required
                    >
                    @error('password_confirmation', 'updatePassword')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="d-flex align-items-center gap-2 mt-4">
            <button type="submit" class="btn btn-primary">
                <i class="feather-lock me-2"></i>Update Password
            </button>

            @if (session('status') === 'password-updated')
                <span 
                    class="text-success d-flex align-items-center" 
                    x-data="{ show: true }" 
                    x-show="show" 
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                >
                    <i class="feather-check-circle me-1"></i>
                    Password updated successfully!
                </span>
            @endif
        </div>
    </form>
</div>
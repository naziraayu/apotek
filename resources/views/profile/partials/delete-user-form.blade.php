<div class="card-header bg-soft-danger">
    <h5 class="card-title text-danger">Delete Account</h5>
    <p class="text-muted mb-0">Permanently delete your account and all associated data.</p>
</div>
<div class="card-body">
    <div class="alert alert-danger d-flex align-items-start" role="alert">
        <i class="feather-alert-triangle me-2 mt-1"></i>
        <div>
            <strong>Warning!</strong> Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </div>
    </div>

    <button 
        type="button" 
        class="btn btn-danger" 
        data-bs-toggle="modal" 
        data-bs-target="#confirmUserDeletionModal"
    >
        <i class="feather-trash-2 me-2"></i>Delete Account
    </button>
</div>

<!-- Delete Account Confirmation Modal -->
<div 
    class="modal fade" 
    id="confirmUserDeletionModal" 
    tabindex="-1" 
    aria-labelledby="confirmUserDeletionModalLabel" 
    aria-hidden="true"
    @if($errors->userDeletion->isNotEmpty()) 
        data-bs-backdrop="static" 
        data-bs-keyboard="false"
    @endif
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="confirmUserDeletionModalLabel">
                    <i class="feather-alert-triangle me-2"></i>Confirm Account Deletion
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="post" action="{{ route('profile.destroy') }}">
                @csrf
                @method('delete')

                <div class="modal-body">
                    <div class="alert alert-warning d-flex align-items-start mb-4" role="alert">
                        <i class="feather-alert-circle me-2 mt-1"></i>
                        <div>
                            <strong>Are you sure you want to delete your account?</strong>
                        </div>
                    </div>

                    <p class="text-muted mb-4">
                        Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
                    </p>

                    <div class="mb-3">
                        <label for="password" class="form-label fw-bold">
                            Password <span class="text-danger">*</span>
                        </label>
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                            placeholder="Enter your password"
                            required
                        >
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="feather-x me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="feather-trash-2 me-2"></i>Delete Account
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($errors->userDeletion->isNotEmpty())
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var deleteModal = new bootstrap.Modal(document.getElementById('confirmUserDeletionModal'));
            deleteModal.show();
        });
    </script>
    @endpush
@endif
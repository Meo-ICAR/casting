@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Privacy Settings') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Consent Management</h5>
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('gdpr.update-consent') }}">
                                @csrf
                                @method('PUT')

                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="marketing_consent"
                                           name="marketing_consent" {{ old('marketing_consent', $user->marketing_consent ? 'checked' : '') }}>
                                    <label class="form-check-label" for="marketing_consent">
                                        I agree to receive marketing communications
                                    </label>
                                    <small class="form-text text-muted">
                                        We'll send you news, updates, and promotional offers.
                                    </small>
                                </div>

                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="newsletter_subscription"
                                           name="newsletter_subscription" {{ old('newsletter_subscription', $user->newsletter_subscription ? 'checked' : '') }}
                                           {{ !$user->marketing_consent ? 'disabled' : '' }}>
                                    <label class="form-check-label" for="newsletter_subscription">
                                        Subscribe to our newsletter
                                    </label>
                                    <small class="form-text text-muted">
                                        Get the latest updates and news directly to your inbox.
                                    </small>
                                </div>

                                <div class="form-group form-check mb-3">
                                    <input type="checkbox" class="form-check-input" id="data_processing_consent"
                                           name="data_processing_consent" {{ old('data_processing_consent', $user->data_processing_consent ? 'checked' : '') }} required>
                                    <label class="form-check-label" for="data_processing_consent">
                                        I consent to the processing of my personal data
                                    </label>
                                    <small class="form-text text-muted">
                                        Required to use our services. <a href="{{ route('gdpr.privacy-policy') }}">Learn more</a>
                                    </small>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Save Preferences') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="mb-0">Data Management</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6>Download Your Data</h6>
                                <p>You can request a copy of all the personal data we have about you.</p>
                                <form method="POST" action="{{ route('gdpr.export-data') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-primary">
                                        {{ __('Export My Data' }}
                                    </button>
                                </form>
                            </div>

                            <div class="mb-3">
                                <h6>Request Data Deletion</h6>
                                <p>You can request to delete all your personal data. This action cannot be undone.</p>
                                <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                    {{ __('Request Data Deletion' )}}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Account Deletion</h5>
                        </div>
                        <div class="card-body">
                            <p>Permanently delete your account and all associated data.</p>
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                                {{ __('Delete My Account') }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account? This action cannot be undone. All your data will be permanently removed.</p>
                <form id="deleteAccountForm" method="POST" action="{{ route('gdpr.delete-account') }}" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" onclick="event.preventDefault(); document.getElementById('deleteAccountForm').submit();">
                    {{ __('Delete My Account') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Enable/disable newsletter subscription based on marketing consent
    document.getElementById('marketing_consent').addEventListener('change', function() {
        const newsletterCheckbox = document.getElementById('newsletter_subscription');
        newsletterCheckbox.disabled = !this.checked;
        if (!this.checked) {
            newsletterCheckbox.checked = false;
        }
    });
</script>
@endpush
@endsection

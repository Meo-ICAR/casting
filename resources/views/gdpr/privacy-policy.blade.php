@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Privacy Policy') }}</div>

                <div class="card-body">
                    <h4>Your Privacy Matters</h4>
                    <p>Last updated: {{ now()->format('F j, Y') }}</p>

                    <div class="mb-4">
                        <h5>1. Information We Collect</h5>
                        <p>We collect information that you provide directly to us when you register, use our services, or communicate with us.</p>
                    </div>

                    <div class="mb-4">
                        <h5>2. How We Use Your Information</h5>
                        <p>We use the information we collect to provide, maintain, and improve our services, to develop new ones, and to protect our users.</p>
                    </div>

                    <div class="mb-4">
                        <h5>3. Information Sharing</h5>
                        <p>We do not share your personal information with companies, organizations, or individuals outside of our organization except as described in this Privacy Policy.</p>
                    </div>

                    <div class="mb-4">
                        <h5>4. Your Rights</h5>
                        <p>You have the right to access, correct, or delete your personal information. You can also object to our processing of your personal information, ask us to restrict processing of your personal information, or request portability of your personal information.</p>
                    </div>

                    @if(!auth()->user()->hasAcceptedPrivacyPolicy())
                    <form method="POST" action="{{ route('gdpr.accept-privacy-policy') }}">
                        @csrf
                        <div class="form-group form-check mb-3">
                            <input type="checkbox" class="form-check-input @error('privacy_policy') is-invalid @enderror" id="privacy_policy" name="privacy_policy" required>
                            <label class="form-check-label" for="privacy_policy">I have read and accept the Privacy Policy</label>
                            @error('privacy_policy')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">
                            {{ __('Accept and Continue') }}
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

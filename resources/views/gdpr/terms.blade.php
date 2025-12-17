@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Terms and Conditions') }}</div>

                <div class="card-body">
                    <h4>Terms of Service</h4>
                    <p>Last updated: {{ now()->format('F j, Y') }}</p>

                    <div class="mb-4">
                        <h5>1. Acceptance of Terms</h5>
                        <p>By accessing or using our services, you agree to be bound by these Terms of Service.</p>
                    </div>

                    <div class="mb-4">
                        <h5>2. Description of Service</h5>
                        <p>Our service provides [brief description of your service].</p>
                    </div>

                    <div class="mb-4">
                        <h5>3. User Responsibilities</h5>
                        <p>You are responsible for maintaining the confidentiality of your account and password and for restricting access to your computer.</p>
                    </div>

                    <div class="mb-4">
                        <h5>4. Limitation of Liability</h5>
                        <p>In no event shall we be liable for any indirect, incidental, special, consequential or punitive damages, or any loss of profits or revenues.</p>
                    </div>

                    @if(!auth()->user()->hasAcceptedTerms())
                    <form method="POST" action="{{ route('gdpr.accept-terms') }}">
                        @csrf
                        <div class="form-group form-check mb-3">
                            <input type="checkbox" class="form-check-input @error('terms') is-invalid @enderror" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">I have read and accept the Terms and Conditions</label>
                            @error('terms')
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

@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Privacy Policy') }}</div>
                <div class="card-body">
                    <h4>Privacy Policy</h4>
                    <p>Effective date: {{ now()->format('F j, Y') }}</p>
                    <p>This Privacy Policy describes how we collect, use, and protect your personal information when you use our services.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

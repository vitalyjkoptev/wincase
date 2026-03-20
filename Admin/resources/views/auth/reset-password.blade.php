@extends('partials.Layouts.master-auth')
@section('title', 'Reset Password | WinCase CRM')
@section('css')
@include('partials.head-css', ['auth' => 'layout-auth'])
@endsection
@section('content')

<section class="auth-page-wrapper position-relative bg-light min-vh-100 d-flex align-items-center justify-content-center">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4 card-bg-fill">
                    <div class="card-body p-4">
                        <div class="text-center mt-2">
                            <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" style="height:52px;max-width:100%;" class="mb-2">
                            <p class="text-muted">Reset your password</p>
                        </div>
                        <div class="p-2 mt-4">
                            <div class="alert alert-warning border-0 text-center mb-2 mx-2" role="alert">
                                Enter your email and instructions will be sent to you!
                            </div>
                            <form action="/auth-signin" method="GET">
                                <div class="mb-4">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter Email" required>
                                </div>
                                <div class="text-center mt-4">
                                    <button class="btn btn-primary w-100" type="submit">Send Reset Link</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="mt-4 text-center">
                    <p class="mb-0">Back to <a href="/auth-signin" class="fw-semibold text-primary text-decoration-underline">Sign In</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

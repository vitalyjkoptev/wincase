@extends('partials.Layouts.master-auth')
@section('title', 'Sign In | WinCase CRM')
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
                            <p class="text-muted">Sign in to continue to WinCase CRM</p>
                        </div>
                        <div class="p-2 mt-4">
                            <form action="/" method="GET">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" placeholder="Enter email" value="yulia@wincase.eu">
                                </div>

                                <div class="mb-3">
                                    <div class="float-end">
                                        <a href="/auth-reset-password" class="text-muted">Forgot password?</a>
                                    </div>
                                    <label class="form-label" for="password-input">Password</label>
                                    <div class="position-relative auth-pass-inputgroup mb-3">
                                        <input type="password" class="form-control pe-5 password-input" placeholder="Enter password" id="password-input" value="12345678">
                                        <button class="btn btn-link position-absolute end-0 top-0 text-decoration-none text-muted password-addon" type="button" id="password-addon">
                                            <i class="ri-eye-fill align-middle"></i>
                                        </button>
                                    </div>
                                </div>

                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="" id="auth-remember-check">
                                    <label class="form-check-label" for="auth-remember-check">Remember me</label>
                                </div>

                                <div class="mt-4">
                                    <button class="btn btn-primary w-100" type="submit">Sign In</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="mb-0">
                        &copy; <script>document.write(new Date().getFullYear())</script> WinCase CRM — wincase.eu
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@extends('partials.layouts.master-auth')
@section('title', 'Sign In | WinCase CRM')
@section('css')
@include('partials.head-css', ['auth' => 'layout-auth'])
@endsection
@section('content')

<section class="auth-page-wrapper position-relative min-vh-100 d-flex align-items-center justify-content-center"
    style="background: linear-gradient(135deg, #042748 0%, #0C192C 50%, #042748 100%);">

    {{-- Background decorative elements --}}
    <div style="position:absolute;top:0;left:0;right:0;bottom:0;overflow:hidden;pointer-events:none;">
        <div style="position:absolute;top:-120px;right:-120px;width:400px;height:400px;border-radius:50%;background:rgba(1,94,167,0.08);"></div>
        <div style="position:absolute;bottom:-80px;left:-80px;width:300px;height:300px;border-radius:50%;background:rgba(1,94,167,0.05);"></div>
    </div>

    <div class="container position-relative" style="z-index:1;">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">

                {{-- Logo block --}}
                <div class="text-center mb-4">
                    <a href="https://wincase.eu" target="_blank">
                        <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase"
                             style="height:60px;max-width:260px;filter:brightness(0) invert(1);" class="mb-2">
                    </a>
                    <p style="color:rgba(255,255,255,0.45);font-size:13px;letter-spacing:1px;margin:0;">CUSTOMER RELATIONSHIP MANAGEMENT</p>
                </div>

                <div class="card border-0 shadow-lg" style="border-radius:16px;overflow:hidden;">
                    {{-- Blue accent top bar --}}
                    <div style="height:4px;background:linear-gradient(90deg,#015EA7,#0278d4,#015EA7);"></div>

                    <div class="card-body p-4 p-md-5">
                        <div class="text-center mb-4">
                            <h5 class="fw-semibold" style="color:#042748;">Welcome Back</h5>
                            <p class="text-muted mb-0" style="font-size:14px;">Sign in to continue to WinCase CRM</p>
                        </div>

                        <div class="alert alert-danger d-none" id="loginError" role="alert"></div>

                        <form id="loginForm" onsubmit="return doLogin(event)">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <div class="mb-3">
                                <label for="login" class="form-label fw-medium">Email or Phone</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-user-line text-muted" id="loginIcon"></i></span>
                                    <input type="text" class="form-control border-start-0" id="login" name="login" placeholder="Email or phone number" required autocomplete="username">
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="float-end">
                                    <a href="/auth-reset-password" style="color:#015EA7;font-size:13px;text-decoration:none;">Forgot password?</a>
                                </div>
                                <label class="form-label fw-medium" for="password-input">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0"><i class="ri-lock-2-line text-muted"></i></span>
                                    <input type="password" class="form-control border-start-0 border-end-0 password-input" placeholder="Enter password" id="password-input" name="password" required>
                                    <button class="input-group-text bg-light border-start-0 password-addon" type="button" id="password-addon" style="cursor:pointer;">
                                        <i class="ri-eye-fill text-muted align-middle"></i>
                                    </button>
                                </div>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="auth-remember-check" name="remember"
                                       style="border-color:#015EA7;">
                                <label class="form-check-label" for="auth-remember-check">Remember me</label>
                            </div>

                            <div class="mt-4">
                                <button class="btn w-100 fw-semibold" type="submit" id="loginBtn"
                                        style="background:#015EA7;color:#fff;border:none;padding:11px;border-radius:10px;font-size:15px;letter-spacing:0.5px;transition:background .2s;"
                                        onmouseover="this.style.background='#0278d4'" onmouseout="this.style.background='#015EA7'">
                                    <span id="loginText">Sign In</span>
                                    <span id="loginSpinner" class="d-none"><span class="spinner-border spinner-border-sm me-1"></span>Signing in...</span>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <p class="mb-0" style="color:rgba(255,255,255,0.4);font-size:13px;">
                        &copy; <script>document.write(new Date().getFullYear())</script>
                        <a href="https://wincase.eu" style="color:rgba(255,255,255,0.6);text-decoration:none;">WinCase</a> — Immigration Bureau
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('js')
<script>
// Toggle password visibility
document.getElementById('password-addon')?.addEventListener('click', function () {
    const input = document.getElementById('password-input');
    input.type = input.type === 'password' ? 'text' : 'password';
    this.querySelector('i').classList.toggle('ri-eye-fill');
    this.querySelector('i').classList.toggle('ri-eye-off-fill');
});

// Switch icon based on input (email vs phone)
document.getElementById('login')?.addEventListener('input', function () {
    const icon = document.getElementById('loginIcon');
    const v = this.value.trim();
    if (v.match(/^\+?\d/)) {
        icon.className = 'ri-phone-line text-muted';
    } else {
        icon.className = 'ri-mail-line text-muted';
    }
});

async function doLogin(e) {
    e.preventDefault();
    const errorEl = document.getElementById('loginError');
    const btn = document.getElementById('loginBtn');
    const text = document.getElementById('loginText');
    const spinner = document.getElementById('loginSpinner');

    errorEl.classList.add('d-none');
    text.classList.add('d-none');
    spinner.classList.remove('d-none');
    btn.disabled = true;

    try {
        const fd = new FormData();
        fd.append('_token', document.querySelector('input[name="_token"]').value);
        fd.append('login', document.getElementById('login').value.trim());
        fd.append('password', document.getElementById('password-input').value);
        if (document.getElementById('auth-remember-check').checked) {
            fd.append('remember', '1');
        }

        const res = await fetch('/login', {
            method: 'POST',
            headers: { 'Accept': 'application/json' },
            body: fd,
        });

        const data = await res.json();

        if (!res.ok || !data.success) {
            errorEl.textContent = data.message || 'Login failed';
            errorEl.classList.remove('d-none');
            return;
        }

        // Save token for API calls from blade JS
        localStorage.setItem('wc_token', data.token);
        document.cookie = 'wc_token=' + data.token + '; path=/; max-age=86400; SameSite=Lax';

        // Redirect to dashboard
        window.location.href = data.redirect || '/';
    } catch (err) {
        errorEl.textContent = 'Connection error. Please try again.';
        errorEl.classList.remove('d-none');
    } finally {
        text.classList.remove('d-none');
        spinner.classList.add('d-none');
        btn.disabled = false;
    }
}
</script>
@endsection

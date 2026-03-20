@extends('partials.layouts.master-client')
@section('title', 'Login — WinCase Client Portal')

@section('nav')
<div></div>
@endsection

@section('nav-right')
<a href="/client-register" class="btn btn-sm btn-outline-primary" data-lang="wc-lg-create-account">Create Account</a>
@endsection

@section('content')
<div class="row justify-content-center" style="min-height:70vh;">
    <div class="col-12 d-flex align-items-center justify-content-center">
        <div class="auth-card w-100">
            <div class="card">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <div class="mb-3">
                            <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" class="wc-page-logo">
                        </div>
                        <h5 class="fw-semibold mb-1" data-lang="wc-lg-client-portal">Client Portal</h5>
                        <p class="text-muted small" data-lang="wc-lg-sign-in-desc">Sign in to manage your immigration case</p>
                    </div>

                    <form id="loginForm">
                        <div class="mb-3">
                            <label class="form-label fw-medium" data-lang="wc-lg-email">Email</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-mail-line text-muted"></i></span>
                                <input type="email" class="form-control border-start-0" id="loginEmail" placeholder="your@email.com" data-lang-placeholder="wc-lg-email-ph" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label class="form-label fw-medium" data-lang="wc-lg-password">Password</label>
                                <a href="#" class="small text-muted" data-bs-toggle="modal" data-bs-target="#forgotModal" data-lang="wc-lg-forgot">Forgot?</a>
                            </div>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="ri-lock-2-line text-muted"></i></span>
                                <input type="password" class="form-control border-start-0 border-end-0" id="loginPassword" placeholder="Enter password" data-lang-placeholder="wc-lg-password-ph" required>
                                <button class="input-group-text bg-light border-start-0 toggle-pass" type="button"><i class="ri-eye-off-line text-muted"></i></button>
                            </div>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="rememberMe">
                            <label class="form-check-label small" for="rememberMe" data-lang="wc-lg-remember-me">Remember me</label>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2 fw-semibold" id="btnSignIn">
                            <i class="ri-login-box-line me-1"></i> <span data-lang="wc-lg-sign-in">Sign In</span>
                        </button>
                    </form>

                    <div class="text-center mt-4">
                        <p class="text-muted small mb-0"><span data-lang="wc-lg-no-account">Don't have an account?</span> <a href="/client-register" class="text-success fw-semibold" data-lang="wc-lg-register-here">Register here</a></p>
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <p class="text-muted text-center small mb-2" data-lang="wc-lg-contact-directly">Or contact us directly:</p>
                        <div class="d-flex justify-content-center gap-3">
                            <a href="tel:+48579266493" class="text-muted small"><i class="ri-phone-line me-1"></i>+48 579 266 493</a>
                            <a href="mailto:info@wincase.eu" class="text-muted small"><i class="ri-mail-line me-1"></i>info@wincase.eu</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Forgot Password Modal -->
<div class="modal fade" id="forgotModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-semibold" data-lang="wc-lg-reset-password">Reset Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small" data-lang="wc-lg-reset-desc">Enter your email address. We'll send you a link to reset your password.</p>
                <div class="mb-3">
                    <label class="form-label fw-medium" data-lang="wc-lg-email">Email</label>
                    <input type="email" class="form-control" id="resetEmail" placeholder="your@email.com" data-lang-placeholder="wc-lg-email-ph">
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button class="btn btn-light" data-bs-dismiss="modal" data-lang="wc-cp-cancel">Cancel</button>
                <button class="btn btn-success" id="btnSendReset"><i class="ri-mail-send-line me-1"></i><span data-lang="wc-lg-send-reset">Send Reset Link</span></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
(function(){
    var t = (window.WcI18n && WcI18n.t) ? WcI18n.t : function(k,f){ return f; };

    // If already authenticated, redirect immediately
    if(localStorage.getItem('wc_token')){
        window.location.href = '/client-dashboard';
        return;
    }

    // Toggle password visibility
    document.querySelector('.toggle-pass').addEventListener('click', function(){
        var inp = document.getElementById('loginPassword');
        var icon = this.querySelector('i');
        if(inp.type === 'password'){
            inp.type = 'text'; icon.className = 'ri-eye-line text-muted';
        } else {
            inp.type = 'password'; icon.className = 'ri-eye-off-line text-muted';
        }
    });

    // Login form
    document.getElementById('loginForm').addEventListener('submit', function(e){
        e.preventDefault();
        var btn = document.getElementById('btnSignIn');
        var origHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + t('wc-lg-signing-in','Signing in...');

        var fd = new FormData();
        fd.append('email', document.getElementById('loginEmail').value);
        fd.append('password', document.getElementById('loginPassword').value);
        if(document.getElementById('rememberMe').checked){
            fd.append('remember', '1');
        }

        fetch('/login', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: fd
        })
        .then(function(res){ return res.json().then(function(data){ return {ok: res.ok, data: data}; }); })
        .then(function(result){
            if(result.ok && result.data.success){
                localStorage.setItem('wc_token', result.data.token);
                if(result.data.user){
                    localStorage.setItem('wc_user', JSON.stringify(result.data.user));
                }
                window.location.href = result.data.redirect || '/client-dashboard';
            } else {
                btn.disabled = false;
                btn.innerHTML = origHtml;
                var msg = (result.data && result.data.message) ? result.data.message : t('wc-lg-invalid-creds','Invalid email or password.');
                Swal.fire({icon:'error', title: t('wc-lg-login-failed','Login Failed'), text: msg, confirmButtonColor:'#015EA7'});
            }
        })
        .catch(function(err){
            btn.disabled = false;
            btn.innerHTML = origHtml;
            Swal.fire({icon:'error', title: t('wc-lg-error','Error'), text: t('wc-lg-network-error','Connection error. Please try again.'), confirmButtonColor:'#015EA7'});
        });
    });

    // Forgot password
    document.getElementById('btnSendReset').addEventListener('click', function(){
        var email = document.getElementById('resetEmail').value;
        if(!email) return;
        var self = this;
        var origHtml = self.innerHTML;
        self.disabled = true;
        self.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>' + t('wc-lg-sending','Sending...');

        var fd = new FormData();
        fd.append('email', email);

        fetch('/api/v1/auth/password/forgot', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: fd
        })
        .then(function(res){ return res.json().then(function(data){ return {ok: res.ok, data: data}; }); })
        .then(function(result){
            bootstrap.Modal.getInstance(document.getElementById('forgotModal')).hide();
            self.disabled = false;
            self.innerHTML = origHtml;
            document.getElementById('resetEmail').value = '';
            if(result.ok && result.data.success){
                Swal.fire({icon:'success', title: t('wc-lg-link-sent','Link Sent'), text: t('wc-lg-check-email','Check your email for the reset link.'), confirmButtonColor:'#015EA7'});
            } else {
                var msg = (result.data && result.data.message) ? result.data.message : t('wc-lg-reset-error','Could not send reset link.');
                Swal.fire({icon:'error', title: t('wc-lg-error','Error'), text: msg, confirmButtonColor:'#015EA7'});
            }
        })
        .catch(function(err){
            self.disabled = false;
            self.innerHTML = origHtml;
            Swal.fire({icon:'error', title: t('wc-lg-error','Error'), text: t('wc-lg-network-error','Connection error. Please try again.'), confirmButtonColor:'#015EA7'});
        });
    });
})();
</script>
@endsection

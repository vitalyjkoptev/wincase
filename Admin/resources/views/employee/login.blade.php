<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Staff Login — WinCase</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Marcellus&display=swap" rel="stylesheet">
    @include('partials.head-css')
    <style>
        body { background: linear-gradient(135deg, #1a1d23 0%, #2d3239 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; font-family: 'DM Sans', sans-serif; }
        h5 { font-family: 'Marcellus', serif; }
        .login-card { max-width: 420px; width: 100%; border-radius: 16px; overflow: hidden; }
        .login-header { background: linear-gradient(135deg, #015EA7 0%, #014d8a 100%); padding: 2rem; text-align: center; }
        .login-header h5 { color: #fff; margin: 0; font-size: 1rem; font-weight: 600; }
        .login-header .logo { text-align: center; }
        .login-header .logo img { height: 52px; max-width: 100%; }
        @media (max-width: 576px) {
            .login-header .logo img { height: 44px; }
        }
        @media (max-width: 400px) {
            .login-header .logo img { height: 38px; }
            .login-header { padding: 1.5rem 1rem; }
            .login-body { padding: 1.5rem; }
        }
        .login-header small { color: rgba(255,255,255,.7); font-size: .8rem; }
        .login-body { padding: 2rem; }
        .staff-badge { display: inline-flex; align-items: center; gap: 4px; background: rgba(255,255,255,.15); padding: 4px 12px; border-radius: 20px; font-size: .7rem; color: #fff; margin-top: .5rem; }
    </style>
</head>
<body>
    <div class="card login-card shadow-lg">
        <div class="login-header">
            <div class="logo"><img src="{{ asset('assets/images/wincase-logo-dark.png') }}" alt="WinCase"></div>
            <h5>Staff Portal</h5>
            <div class="staff-badge"><i class="ri-shield-keyhole-line"></i> Authorized Personnel Only</div>
        </div>
        <div class="login-body">
            <form id="loginForm">
                <div class="mb-3">
                    <label class="form-label fw-semibold">Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-mail-line"></i></span>
                        <input type="email" class="form-control" id="email" placeholder="your@wincase.eu" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="ri-lock-line"></i></span>
                        <input type="password" class="form-control" id="password" placeholder="Enter password" required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePass()"><i class="ri-eye-off-line" id="eyeIcon"></i></button>
                    </div>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember">
                        <label class="form-check-label" for="remember" style="font-size:.85rem;">Remember me</label>
                    </div>
                    <a href="javascript:void(0)" onclick="forgotPassword()" style="font-size:.85rem;">Forgot password?</a>
                </div>
                <button type="submit" class="btn w-100 py-2 fw-semibold" style="background:#015EA7;color:#fff;border:none;">
                    <i class="ri-login-box-line me-1"></i> Sign In
                </button>
            </form>
            <div class="text-center mt-4">
                <small class="text-muted">Need access? Contact your administrator</small><br>
                <small class="text-muted"><i class="ri-phone-line"></i> IT Support: +48 579 266 493</small>
            </div>
        </div>
    </div>

    <script>
    function togglePass(){
        var p = document.getElementById('password');
        var i = document.getElementById('eyeIcon');
        if(p.type === 'password'){ p.type='text'; i.className='ri-eye-line'; }
        else { p.type='password'; i.className='ri-eye-off-line'; }
    }
    function forgotPassword(){
        var email = prompt('Enter your email to receive a password reset link:');
        if(email) alert('Reset link sent to ' + email);
    }
    document.getElementById('loginForm').addEventListener('submit', function(e){
        e.preventDefault();
        var btn = this.querySelector('button[type=submit]');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Signing in...';
        setTimeout(function(){ window.location.href = '/staff-dashboard'; }, 1000);
    });
    </script>
</body>
</html>

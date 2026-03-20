<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <title>@yield('title', 'WinCase — Client Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WinCase Client Portal — Manage your immigration case, documents and communication.">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Marcellus&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style">
    <link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" id="app-style">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.min.css') }}?v={{ time() }}" id="custom-style">
    <link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">

    <style>
        :root {
            --wc-primary: #015EA7;
            --wc-primary-rgb: 1,94,167;
            --wc-dark: #000000;
            --wc-font: 'DM Sans', sans-serif;
            --wc-font-heading: 'Marcellus', serif;
            /* Override Bootstrap success → WinCase blue */
            --bs-success: #015EA7;
            --bs-success-rgb: 1,94,167;
            --bs-success-text-emphasis: #013d6e;
            --bs-success-bg-subtle: #cde3f4;
            --bs-success-border-subtle: #9bc7e9;
        }
        body { background: #f5f6fa; font-family: var(--wc-font); }
        h1, h2, h3, h4, h5, h6 { font-family: var(--wc-font-heading); }
        [data-bs-theme="dark"] body { background: #151821; }

        /* ── Top navbar ── */
        .client-navbar {
            background: #fff;
            border-bottom: 1px solid rgba(0,0,0,.08);
            padding: .75rem 0;
            position: sticky; top: 0; z-index: 1030;
        }
        [data-bs-theme="dark"] .client-navbar { background: #1a1d23; border-color: rgba(255,255,255,.06); }

        .client-navbar .wc-logo { font-size: 1.5rem; font-weight: 800; letter-spacing: .5px; text-decoration: none; color: var(--wc-primary); }
        [data-bs-theme="dark"] .client-navbar .wc-logo { color: #4d9fd4; }
        .client-navbar .wc-logo span { color: var(--wc-dark); }
        [data-bs-theme="dark"] .client-navbar .wc-logo span { color: #fff; }

        .client-navbar .nav-link { color: #495057; font-weight: 500; font-size: .875rem; padding: .5rem 1rem; border-radius: .375rem; transition: all .15s; }
        .client-navbar .nav-link:hover, .client-navbar .nav-link.active { color: var(--wc-primary); background: rgba(var(--wc-primary-rgb),.08); }
        [data-bs-theme="dark"] .client-navbar .nav-link { color: #adb5bd; }

        /* ── Wizard ── */
        .wizard-steps { display: flex; align-items: center; gap: 0; margin-bottom: 2rem; }
        .wizard-step { flex: 1; text-align: center; position: relative; }
        .wizard-step .step-num {
            width: 38px; height: 38px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: .875rem; border: 2px solid #dee2e6; color: #6c757d; background: #fff; position: relative; z-index: 2; transition: all .3s;
        }
        .wizard-step.active .step-num { border-color: var(--wc-primary); color: #fff; background: var(--wc-primary); }
        .wizard-step.done .step-num { border-color: var(--wc-primary); color: #fff; background: var(--wc-primary); }
        .wizard-step .step-label { display: block; font-size: .75rem; color: #6c757d; margin-top: .375rem; font-weight: 500; }
        .wizard-step.active .step-label { color: var(--wc-primary); font-weight: 600; }
        .wizard-step.done .step-label { color: var(--wc-primary); }
        .wizard-step::after {
            content: ''; position: absolute; top: 19px; left: 50%; width: 100%; height: 2px; background: #dee2e6; z-index: 1;
        }
        .wizard-step:last-child::after { display: none; }
        .wizard-step.done::after { background: var(--wc-primary); }
        [data-bs-theme="dark"] .wizard-step .step-num { background: #1a1d23; border-color: #495057; }
        [data-bs-theme="dark"] .wizard-step::after { background: #495057; }

        .wizard-panel { display: none; }
        .wizard-panel.active { display: block; }

        /* ── Auth card ── */
        .auth-card { max-width: 460px; margin: 0 auto; }
        .auth-card .card { border: none; box-shadow: 0 2px 16px rgba(0,0,0,.06); border-radius: 1rem; }
        [data-bs-theme="dark"] .auth-card .card { background: #1a1d23; box-shadow: 0 2px 16px rgba(0,0,0,.3); }

        /* ── Client dashboard ── */
        .case-timeline { position: relative; padding-left: 2rem; }
        .case-timeline::before { content: ''; position: absolute; left: 14px; top: 0; bottom: 0; width: 2px; background: #dee2e6; }
        .case-timeline .tl-item { position: relative; padding-bottom: 1.5rem; }
        .case-timeline .tl-dot {
            position: absolute; left: -2rem; top: 2px; width: 12px; height: 12px; border-radius: 50%;
            border: 2px solid #dee2e6; background: #fff;
        }
        .case-timeline .tl-item.done .tl-dot { border-color: var(--wc-primary); background: var(--wc-primary); }
        .case-timeline .tl-item.current .tl-dot { border-color: #f0ad4e; background: #f0ad4e; box-shadow: 0 0 0 4px rgba(240,173,78,.2); }

        .stat-card { border: none; border-radius: .75rem; box-shadow: 0 1px 6px rgba(0,0,0,.04); transition: transform .15s; }
        .stat-card:hover { transform: translateY(-2px); }

        /* ── Unified logo & text sizes ── */
        .wc-page-logo { height: 52px; display: block; margin: 0 auto; }

        /* ── Mobile responsive ── */
        @media (max-width: 576px) {
            .client-navbar .wc-logo img { height: 30px !important; }
            .wc-page-logo { height: 44px; }
            .container.py-4 { padding-left: 12px; padding-right: 12px; }
        }
        @media (max-width: 400px) {
            .client-navbar .wc-logo img { height: 26px !important; }
            .wc-page-logo { height: 38px; }
        }
    </style>
    @yield('css')
</head>
<body>
    <!-- Client Navbar -->
    <nav class="client-navbar">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between">
                <a href="/client-dashboard" class="wc-logo"><img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" style="height:36px;"></a>
                @yield('nav')
                <div class="d-flex align-items-center gap-2">
                    @yield('nav-right')
                    <!-- Language switcher -->
                    <div class="dropdown" id="language-dropdown">
                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown" style="width:34px;height:34px;padding:0;">
                            <img src="{{ asset('assets/images/flags/us.svg') }}" alt="" style="width:18px;height:18px;border-radius:50%;">
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-lang-switch="en"><img src="{{ asset('assets/images/flags/us.svg') }}" style="width:18px;height:18px;border-radius:50%;">English</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-lang-switch="pl"><img src="{{ asset('assets/images/flags/pl.svg') }}" style="width:18px;height:18px;border-radius:50%;">Polski</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#" data-lang-switch="ua"><img src="{{ asset('assets/images/flags/ua.svg') }}" style="width:18px;height:18px;border-radius:50%;">Українська</a></li>
                        </ul>
                    </div>
                    <!-- Theme toggle -->
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light rounded-circle" data-bs-toggle="dropdown" style="width:34px;height:34px;padding:0;">
                            <i class="ri-sun-line"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#" onclick="document.documentElement.setAttribute('data-bs-theme','light')"><i class="ri-sun-line me-2"></i><span data-lang="wc-cp-theme-light">Light</span></a></li>
                            <li><a class="dropdown-item" href="#" onclick="document.documentElement.setAttribute('data-bs-theme','dark')"><i class="ri-moon-line me-2"></i><span data-lang="wc-cp-theme-dark">Dark</span></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="text-center py-3 text-muted small border-top mt-auto">
        &copy; <script>document.write(new Date().getFullYear())</script> WinCase — wincase.eu &middot; <span data-lang="wc-cp-immigration-bureau">Immigration Bureau</span>
    </footer>

    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/sweetalert2/sweetalert2.min.js') }}"></script>
    <script src="{{ asset('assets/js/i18n.js') }}?v={{ time() }}"></script>
    @yield('js')
</body>
</html>

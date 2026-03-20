<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-577W7MRN');</script>
    <!-- End Google Tag Manager -->
    <title>@yield('title', 'WinCase — Staff Portal')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">

    @include('partials.datatable-css')
    @yield('css')
    @include('partials.head-css')
    <style>
        :root {
            --bs-success: #015EA7;
            --bs-success-rgb: 1,94,167;
            --bs-success-text-emphasis: #013d6e;
            --bs-success-bg-subtle: #cde3f4;
            --bs-success-border-subtle: #9bc7e9;
        }
    </style>
</head>
<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-577W7MRN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

    @include('partials.header')

    <!-- START SIDEBAR (Employee version) -->
    <aside class="app-sidebar">
        {{-- LOGO: НЕ МЕНЯТЬ без явной просьбы пользователя! --}}
        <div class="app-sidebar-logo px-6 justify-content-center align-items-center">
            <a href="/staff-dashboard">
                <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" class="app-sidebar-logo-default" style="height:32px;">
                <img src="{{ asset('assets/images/wincase-icon.png') }}" alt="WC" class="app-sidebar-logo-minimize" style="height:28px;">
            </a>
        </div>
        <nav class="app-sidebar-menu nav nav-pills flex-column fs-6" id="sidebarMenu" aria-label="Staff navigation">
            @include('partials.sidebar-employee-items')
        </nav>
    </aside>
    <!-- END SIDEBAR -->
    <div class="horizontal-overlay"></div>

    <!-- Small screen sidebar -->
    <div class="offcanvas offcanvas-md offcanvas-start small-screen-sidebar" data-bs-scroll="true" tabindex="-1" id="smallScreenSidebar">
        <div class="offcanvas-header hstack border-bottom">
            {{-- LOGO: НЕ МЕНЯТЬ без явной просьбы пользователя! --}}
            <div class="app-sidebar-logo">
                <a href="/staff-dashboard">
                    <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" style="height:32px;">
                </a>
            </div>
            <button type="button" class="btn-close bg-transparent" data-bs-dismiss="offcanvas"><i class="ri-close-line"></i></button>
        </div>
        <div class="offcanvas-body p-0">
            <aside class="app-sidebar">
                <nav class="app-sidebar-menu nav nav-pills flex-column fs-6">
                    @include('partials.sidebar-employee-items')
                </nav>
            </aside>
        </div>
    </div>

    @include('partials.preloader')

    <main class="app-wrapper">
        <div class="app-container">
            @include('partials.breadcrumb')
            @yield('content')
            @include('partials.bottom-wrapper')
            @include('partials.datatable-script')
            <script src="{{ asset('assets/js/crm-api.js') }}"></script>
            @yield('js')

<script>
(function(){
  function syncSidebar(){
    var theme = document.documentElement.getAttribute('data-bs-theme');
    var sidebar = (theme === 'dark') ? 'dark-sidebar' : 'light-sidebar';
    document.documentElement.setAttribute('data-sidebar', sidebar);
    sessionStorage.setItem('data-sidebar', sidebar);
  }
  var obs = new MutationObserver(function(mutations){
    mutations.forEach(function(m){ if(m.attributeName === 'data-bs-theme') syncSidebar(); });
  });
  obs.observe(document.documentElement, {attributes: true, attributeFilter:['data-bs-theme']});
  syncSidebar();
})();
</script>
</body>
</html>

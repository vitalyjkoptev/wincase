<!DOCTYPE html>
<html lang="en" class="h-100">
<head>
    <meta charset="utf-8">
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

    @include('partials.header')

    <!-- START SIDEBAR (Employee version) -->
    <aside class="app-sidebar">
        <div class="app-sidebar-logo px-6 justify-content-center align-items-center">
            <a href="/staff-dashboard" class="wincase-logo">
                <span class="app-sidebar-logo-default fw-bold fs-4 wincase-logo-text">WIN<span class="wincase-logo-accent">CASE</span></span>
                <span class="app-sidebar-logo-minimize fw-bold fs-5 wincase-logo-text">W<span class="wincase-logo-accent">C</span></span>
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
            <div class="app-sidebar-logo">
                <a href="/staff-dashboard" class="wincase-logo">
                    <span class="app-sidebar-logo-default fw-bold fs-4 wincase-logo-text">WIN<span class="wincase-logo-accent">CASE</span></span>
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

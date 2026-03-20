<!-- START SIDEBAR -->
<aside class="app-sidebar">
    <!-- START BRAND LOGO -->
    {{-- LOGO: НЕ МЕНЯТЬ без явной просьбы пользователя! --}}
    <div class="app-sidebar-logo px-6 justify-content-center align-items-center">
        <a href="/">
            <img src="{{ asset('assets/images/wincase-logo.png') }}" alt="WinCase" class="app-sidebar-logo-default" style="height:32px;">
            <img src="{{ asset('assets/images/wincase-icon.png') }}" alt="WC" class="app-sidebar-logo-minimize" style="height:28px;">
        </a>
    </div>
    <!-- END BRAND LOGO -->
    <nav class="app-sidebar-menu nav nav-pills flex-column fs-6" id="sidebarMenu" aria-label="Main navigation">
        @include('partials.sidebar-menu-items')
    </nav>
</aside>
<!-- END SIDEBAR -->
<div class="horizontal-overlay"></div>

<!-- Mobile sidebar overlay -->
<div class="mobile-sidebar-overlay"></div>

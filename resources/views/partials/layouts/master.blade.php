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
    <title>@yield('title', 'WinCase CRM - Admin Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="WinCase CRM - Customer Relationship Management System. Admin Dashboard.">
    <meta name="keywords" content="WinCase CRM, admin dashboard, crm, customer relationship management, leads, clients, cases">
    <meta content="WinCase" name="author">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="asset-url" content="{{ asset('') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="WinCase CRM - Admin Dashboard">
    <meta property="og:description" content="WinCase CRM - Customer Relationship Management System.">
    <meta property="og:url" content="https://wincase.eu">
    <meta property="og:site_name" content="WinCase CRM">

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
    @include('partials.sidebar')
    @include('partials.preloader')


    <main class="app-wrapper">
        <div class="app-container">

            @include('partials.breadcrumb')

            <!-- end page title -->

            @yield('content')
            @include('partials.bottom-wrapper')
            @include('partials.datatable-script')
            <script src="{{ asset('assets/js/crm-api.js') }}"></script>
            @yield('js')

<script>
// WinCase: force sync sidebar with dark/light theme
(function(){
  function syncSidebar(){
    var theme = document.documentElement.getAttribute('data-bs-theme');
    var sidebar = (theme === 'dark') ? 'dark-sidebar' : 'light-sidebar';
    document.documentElement.setAttribute('data-sidebar', sidebar);
    sessionStorage.setItem('data-sidebar', sidebar);
  }
  // Watch for theme attribute changes
  var obs = new MutationObserver(function(mutations){
    mutations.forEach(function(m){
      if(m.attributeName === 'data-bs-theme') syncSidebar();
    });
  });
  obs.observe(document.documentElement, {attributes: true, attributeFilter:['data-bs-theme']});
  // Sync on load
  syncSidebar();
})();
</script>
</body>

</html>

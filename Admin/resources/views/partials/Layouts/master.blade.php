<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
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

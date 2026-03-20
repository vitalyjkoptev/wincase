{{-- master_auth.blade.php  file  --}}
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
    <meta name="description" content="WinCase CRM - Customer Relationship Management System for Immigration Bureau.">
    <meta name="keywords" content="WinCase CRM, admin dashboard, crm, immigration, leads, clients, cases">
    <meta content="WinCase" name="author">
    <link rel="shortcut icon" href="{{ asset('assets/images/Favicon.png') }}">

    <meta property="og:locale" content="en_US">
    <meta property="og:type" content="article">
    <meta property="og:title" content="WinCase CRM - Admin Dashboard">
    <meta property="og:description" content="WinCase CRM - Customer Relationship Management System.">
    <meta property="og:url" content="https://wincase.eu">
    <meta property="og:site_name" content="WinCase CRM">
    <script>
        window.DEFAULT_VALUES = window.DEFAULT_VALUES || {};
        window.DEFAULT_VALUES.AUTH_LAYOUT = true;
    </script>
    @yield('css')
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

<body class="auth-page">
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-577W7MRN"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    @include('partials.preloader')

    @yield('content')

    @include('partials.vendor-scripts')

    @yield('js')

</body>

</html>

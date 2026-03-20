<!DOCTYPE html>
<html lang="en" class="h-100">

<head>
    <meta charset="utf-8">
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

            @yield('js')

</body>

</html>

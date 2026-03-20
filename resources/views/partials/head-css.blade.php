{{-- Layout JS --}}
@if(!empty($horizontal))
    <script src="{{ asset('assets/js/layout/' . $horizontal . '.js') }}"></script>
@elseif(!empty($twocolumn))
    <script src="{{ asset('assets/js/layout/' . $twocolumn . '.js') }}"></script>
@elseif(!empty($compact))
    <script src="{{ asset('assets/js/layout/' . $compact . '.js') }}"></script>
@elseif(!empty($semibox))
    <script src="{{ asset('assets/js/layout/' . $semibox . '.js') }}"></script>
@elseif(!empty($smallicon))
    <script src="{{ asset('assets/js/layout/' . $smallicon . '.js') }}"></script>
@elseif(!empty($auth))
    <script src="{{ asset('assets/js/layout/' . $auth . '.js') }}"></script>
@else
    <script src="{{ asset('assets/js/layout/layout-default.js') }}"></script>
@endif

<script src="{{ asset('assets/js/layout/layout.js') }}?v={{ time() }}"></script>

{{-- Google Fonts (match wincase.eu) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&family=Marcellus&display=swap" rel="stylesheet">

{{-- CSS Dependencies --}}
<link rel="stylesheet" href="{{ asset('assets/libs/choices.js/public/assets/styles/choices.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/simplebar/simplebar.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/icons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/libs/sweetalert2/sweetalert2.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style">
<link rel="stylesheet" href="{{ asset('assets/css/app.min.css') }}" id="app-style">
<link rel="stylesheet" href="{{ asset('assets/css/custom.min.css') }}?v={{ time() }}" id="custom-style">

@php
    $settings = settings();
@endphp
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title', $settings->app_name)</title>
    <meta
        content={{ $settings->meta_description }}
        name="description">
    <meta
        content="{{ $settings->app_name }}, Party-list, Latest news, News, About us, Contact us"
        name="keywords">
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('favicon/apple-touch-icon.png') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon/favicon-32x32.png') }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon/favicon-16x16.png') }}">
    {{-- <link rel="icon" type="image/x-icon" sizes="16x16"
        href="{{ asset('favicon/favicon.ico?v=' . date('YmdHi')) }}"> --}}
    {{-- <link rel="manifest" href="{{ asset('favicon/manifest.json') }}"> --}}
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": {{ $settings->app_name }},
            "url": "https://www.Maki.com",
            "description": {{ $settings->meta_description }},
            "publisher": {
              "@type": "Organization",
              "name": {{ $settings->app_name }},
              "url": "https://www.Maki.com",
            },
        }
    </script>
    <link href="{{ asset('css/styles.css?v=' . date('YmdHi')) }}" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/aos@next/dist/aos.css" />
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon/favicon-32x32.png') }}" />
    <script data-search-pseudo-elements="" defer=""
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css"
        integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />


    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">

    @yield('styles')

    @include('app._partials._theme')

</head>

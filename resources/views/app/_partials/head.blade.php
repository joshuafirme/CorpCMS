@php
    $settings = settings();
@endphp

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="{{ $settings->meta_description }}" />

    <meta property="og:title" content="{{ $settings->app_name }}">
    <meta property="og:description" content="{{ $settings->meta_description }}">
    <meta property="og:image"
        content="https://www.kamalayanpartylist.org/storage/uploads/img/sliders/Website%20Banner%201_674d4f796c678.png">
    <meta property="og:url" content="https://www.kamalayanpartylist.org/">

    <meta name="twitter:title" content="{{ $settings->app_name }}">
    <meta name="twitter:description" content="{{ $settings->meta_description }}">
    <meta name="twitter:url"
        content="https://www.kamalayanpartylist.org/storage/uploads/img/sliders/Website%20Banner%201_674d4f796c678.png">
    <meta name="twitter:card" content="summary">

    <meta name="author" content="" />
    <title>@yield('title', $settings->app_name)</title>
    <meta
        content="{{ $settings->app_name }}, Party-list, Latest news, News, inclusive governance, community empowerment, sustainable development, Filipino advocacy, political party Philippines, progressive solutions, social change, community programs, public service, leadership Philippines, grassroots initiatives, national development, advocacy Philippines, empowering communities"
        name="keywords">

    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="24x24" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset('favicon.ico') }}">

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": {{ $settings->app_name }},
            "url": "{{ env('APP_URL') }}",
            "description": {{ $settings->meta_description }},
            "publisher": {
              "@type": "Organization",
              "name": {{ $settings->app_name }},
              "url": "{{ env('APP_URL') }}",
            },
        }
    </script>

    <link href="{{ asset('css/styles.css?v=10') }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('libs/css/aos.css') }}">

    <script data-search-pseudo-elements="" defer="" src="{{ asset('libs/js/font-awesome-6.4.0.js') }}"
        crossorigin="anonymous"></script>

    <script data-search-pseudo-elements="" defer="" rel="stylesheet" src="{{ asset('libs/js/feather.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('libs/css/splide.min.css') }}">

    @yield('styles')

    @include('app._partials._theme')

</head>

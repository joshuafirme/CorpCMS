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
    <meta property="og:url" content="https://www.kamalayanpartylist.org/">

    <meta name="twitter:title" content="{{ $settings->app_name }}">
    <meta name="twitter:description" content="{{ $settings->meta_description }}">
    <meta name="twitter:card" content="summary">

    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $settings->app_name)</title>
    <meta
        content="{{ $settings->app_name }}, Party-list, Latest news, News, inclusive governance, community empowerment, sustainable development, Filipino advocacy, political party Philippines, progressive solutions, social change, community programs, public service, leadership Philippines, grassroots initiatives, national development, advocacy Philippines, empowering communities"
        name="keywords">

    <link rel="shortcut icon" href="{{ asset("favicon.ico?v=$settings->app_version") }}" type="image/x-icon">
    <link rel="icon" href="{{ asset("favicon.ico?v=$settings->app_version") }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="24x24" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset("favicon.ico?v=$settings->app_version") }}">

    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
    <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "WebSite",
            "name": "{{ $settings->app_name }}",
            "url": "{{ env('APP_URL') }}",
            "description": "{{ $settings->meta_description }}",
            "publisher": {
              "@type": "Organization",
              "name": "{{ $settings->app_name }}",
              "url": "{{ env('APP_URL') }}",
            },
        }
    </script>
    <style>

                .newsletter {
                    background-color: #f9fafb;
                    padding: 2rem;
                    border-radius: 1rem;
                    max-width: 480px;
                    margin: 2rem auto;
                    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
                    font-family: 'Inter', sans-serif;
                }

                .newsletter-form {
                    display: flex;
                    flex-direction: column;
                    gap: 1rem;
                }

                .newsletter .title {
                    font-size: 1.5rem;
                    font-weight: 600;
                    margin-bottom: 0.25rem;
                    text-align: center;
                }

                .newsletter .subtitle {
                    font-size: 0.95rem;
                    color: #6b7280;
                    text-align: center;
                    margin-bottom: 1rem;
                }

                .form-group {
                    display: flex;
                    gap: 0.5rem;
                }

                .form-group input[type="email"] {
                    flex: 1;
                    padding: 0.75rem 1rem;
                    border: 1px solid #d1d5db;
                    border-radius: 0.5rem;
                    font-size: 1rem;
                    outline: none;
                    transition: border-color 0.2s ease;
                }

                .form-group input:focus {
                    border-color: #3b82f6;
                }

                .form-group button {
                    padding: 0.75rem 1.25rem;
                    background-color: #3b82f6;
                    color: #fff;
                    font-size: 1rem;
                    border: none;
                    border-radius: 0.5rem;
                    cursor: pointer;
                    transition: background-color 0.2s ease;
                }

                .form-group button:hover {
                    background-color: #2563eb;
                }
    </style>

    <link href="{{ asset("css/styles.css?v=$settings->app_version") }}" rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('libs/css/aos.css') }}">

    <script data-search-pseudo-elements="" defer="" src="{{ asset('libs/js/font-awesome-6.4.0.js') }}"
        crossorigin="anonymous"></script>

    <script data-search-pseudo-elements="" defer="" rel="stylesheet" src="{{ asset('libs/js/feather.min.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('libs/css/splide.min.css') }}">

    @yield('styles')

    @include('app._partials._theme')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

</head>

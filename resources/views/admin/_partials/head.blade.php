<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>@yield('title') | {{ settings()->app_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/litepicker/dist/css/litepicker.css" rel="stylesheet" />
    <link href="{{ asset("css/app.css?v=".rand(0,9999)) }}" rel="stylesheet" />
    <link href="{{ asset("css/choices.min.css") }}" rel="stylesheet" />
    <link href="{{ asset("css/iphone_frame.css") }}" rel="stylesheet" />
    <link href="{{ asset("css/loader.css") }}" rel="stylesheet" />

    <link rel="shortcut icon" href="{{ asset("favicon.ico?v=$settings->app_version") }}" type="image/x-icon">
    <link rel="icon" href="{{ asset("favicon.ico?v=$settings->app_version") }}" type="image/x-icon">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="24x24" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    <link rel="icon" type="image/png" sizes="48x48" href="{{ asset("favicon.ico?v=$settings->app_version") }}">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.css"
        integrity="sha512-8D+M+7Y6jVsEa7RD6Kv/Z7EImSpNpQllgaEIQAtqHcI0H6F4iZknRj0Nx1DCdB+TwBaS+702BGWYC0Ze2hpExQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script data-search-pseudo-elements="" defer=""
        src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/js/all.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/feather-icons/4.29.0/feather.min.js" crossorigin="anonymous">
    </script>
    
    @include('app._partials._theme')
</head>

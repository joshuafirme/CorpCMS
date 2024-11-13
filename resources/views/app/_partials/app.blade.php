<!DOCTYPE html>
<html>

@include('app._partials.head')

<body>
    @php
        $user = auth()->check() ? auth()->user() : '';
        $primary_color = settings()->primary_color;
        $secondary_color = settings()->secondary_color;
        $primary_rgb = hexToRgb(settings()->primary_color);
    @endphp
    <style>
        /* Typography and Background Utility Classes */
        .text-primary {
            color: {{ $primary_color }} !important;
        }

        .text-secondary {
            color: #{{ $secondary_color }} !important;
        }

        .bg-primary {
            background-color: {{ $primary_color }} !important;
        }

        .bg-secondary {
            background-color: #{{ $secondary_color }} !important;
        }

        /* Button Classes */
        .btn-primary {
            background-color: {{ $primary_color }} !important;
            border-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .btn-secondary {
            background-color: #{{ $secondary_color }} !important;
            border-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        .btn-primary:hover {
            background-color: #0056b3 !important;
            border-color: #004085 !important;
        }

        .btn-secondary:hover {
            background-color: #5a6268 !important;
            border-color: #545b62 !important;
        }

        /* Navbar and Nav Link Colors */
        .navbar.bg-primary {
            background-color: {{ $primary_color }} !important;
        }

        .navbar.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
        }

        .nav-link.text-primary {
            color: {{ $primary_color }} !important;
        }

        .nav-link.text-secondary {
            color: #{{ $secondary_color }} !important;
        }

        .nav-link.text-primary:hover {
            color: #004085 !important;
        }

        .nav-link.text-secondary:hover {
            color: #4a5459 !important;
        }

        /* Alert Colors */
        .alert-primary {
            background-color: #cfe2ff !important;
            color: #084298 !important;
        }

        .alert-secondary {
            background-color: #e2e3e5 !important;
            color: #41464b !important;
        }

        .alert-primary .alert-link {
            color: #084298 !important;
        }

        .alert-secondary .alert-link {
            color: #41464b !important;
        }

        /* Badge Colors */
        .badge-primary {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .badge-secondary {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        /* List Group Item Colors */
        .list-group-item-primary {
            background-color: #cfe2ff !important;
            color: #084298 !important;
        }

        .list-group-item-secondary {
            background-color: #e2e3e5 !important;
            color: #41464b !important;
        }

        /* Form and Input Colors */
        .form-control.bg-primary {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .form-control.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        .custom-file-label.bg-primary {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .custom-file-label.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        /* Progress Bar Colors */
        .progress-bar.bg-primary {
            background-color: {{ $primary_color }} !important;
        }

        .progress-bar.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
        }

        /* Table Colors */
        .table-primary {
            background-color: #cfe2ff !important;
            color: #084298 !important;
        }

        .table-secondary {
            background-color: #e2e3e5 !important;
            color: #41464b !important;
        }

        /* Pills and Tabs */
        .nav-pills .nav-link.bg-primary {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .nav-pills .nav-link.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        .nav-pills .nav-link.bg-primary:hover {
            background-color: #0056b3 !important;
        }

        .nav-pills .nav-link.bg-secondary:hover {
            background-color: #5a6268 !important;
        }

        /* Pagination */
        .page-item.active .page-link.bg-primary {
            background-color: {{ $primary_color }} !important;
            border-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .page-item.active .page-link.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
            border-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        /* Dropdown Item Colors */
        .dropdown-item.text-primary {
            color: {{ $primary_color }} !important;
        }

        .dropdown-item.text-secondary {
            color: #{{ $secondary_color }} !important;
        }

        .dropdown-item.text-primary:hover {
            color: #004085 !important;
        }

        .dropdown-item.text-secondary:hover {
            color: #4a5459 !important;
        }

        /* Accordion and Collapse */
        .accordion-button.bg-primary {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
        }

        .accordion-button.bg-secondary {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
        }

        .accordion-button.bg-primary:hover {
            background-color: #0056b3 !important;
        }

        .accordion-button.bg-secondary:hover {
            background-color: #5a6268 !important;
        }

        /* Outlined Button Classes */
        .btn-outline-primary {
            color: {{ $primary_color }} !important;
            border-color: {{ $primary_color }} !important;
        }

        .btn-outline-secondary {
            color: #{{ $secondary_color }} !important;
            border-color: #{{ $secondary_color }} !important;
        }

        .btn-outline-primary:hover {
            background-color: {{ $primary_color }} !important;
            color: #fff !important;
            border-color: {{ $primary_color }} !important;
        }

        .btn-outline-secondary:hover {
            background-color: #{{ $secondary_color }} !important;
            color: #fff !important;
            border-color: #{{ $secondary_color }} !important;
        }

        /* Link Colors */
        a.text-primary {
            color: {{ $primary_color }} !important;
        }

        a.text-secondary {
            color: #{{ $secondary_color }} !important;
        }

        a.text-primary:hover {
            color: #004085 !important;
        }

        a.text-secondary:hover {
            color: #4a5459 !important;
        }

        
        .splide__slide {
            position: relative;
        }

        .image-title {
            position: absolute;
            text-align: center;
            width: 100%;
            position: absolute;
            bottom: 30px;
            color: white;
            background-color: rgba({{$primary_rgb['r']}}, {{$primary_rgb['g']}}, {{$primary_rgb['b']}}, 0.5);
            padding: 5px;
            font-size: 2rem;
        }
    </style>
    <div id="layoutDefault">
        <div id="layoutDefault_content">
            <main id="app" v-cloak>
                @include('app._partials.topnav')

                @yield('content')
            </main>
            {{-- Firebase Vue script --}}
            <div id="fcm" v-cloak></div>
        </div>
        @include('app._partials.foot')
    </div>

    <script>
        window.Laravel = {
            appUrl: "{{ env('APP_URL') }}",
            user_token: "{{ $user ? $user->token : '' }}",
            user_id: "{{ $user ? $user->id : '' }}",
            user_type: "{{ $user ? $user->user_type : '' }}",
            recaptcha_key: "{{ env('RECAPTCHA_SITE_KEY') }}"
        };
    </script>

    @include('app._partials.scripts')

    @yield('scripts')
</body>

<!DOCTYPE html>
<html>

@include('app._partials.head')

<body>
    @php
        $user = auth()->check() ? auth()->user() : '';
    @endphp
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

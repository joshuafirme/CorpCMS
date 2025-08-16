<!DOCTYPE html>
<html>

@include('admin._partials.head')

@php
    $user = auth()->check() ? auth()->user() : '';
    if (isset($user->profile_img)) {
        $user->profile_img = env('APP_URL') . $user->profile_img;
    }
    $profile_img = $user->profile_img ? $user->profile_img : asset('assets/img/profile-1.png');
@endphp

<body class="nav-fixed">

    @include('admin._partials.topnav')

    <div id="layoutSidenav">

        @include('admin._partials.sidenav')

        <div id="layoutSidenav_content">

            <main id="app" v-cloak>

                @yield('content')

            </main>
        </div>

    </div>

    @include('admin._partials.scripts')

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        window.Laravel = {
            appUrl: "{{ env('APP_URL') }}",
            user_token: "{{ auth()->user()->token }}"
        };
    </script>
    @yield('scripts')

</body>

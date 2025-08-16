<!-- Navbar-->
@php
    $user = request()->user();
@endphp
<nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
    <div class="container px-5">
        <a class="navbar-brand" href="/">
            {{-- Unseen Wins --}}
            {{-- <img style="width: 80px; height:auto;" class="img-fluid rounded"  src="{{ asset("/assets/img/unseen-win.png") }}" alt="logo"> --}}
            <h4 style="color:#737373;">Xavier Nunag</h4>
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                data-feather="menu"></i></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto me-lg-5">

                @foreach (Utils::getNavPages() as $page)
                    <li class="nav-item">
                        @php
                            $link = Str::lower( $page->slug == "home" ? "" : $page->slug );
                        @endphp

                        <a class="nav-link" href="/{{ $link }}">
                            {{ $page->title }}
                        </a>
                    </li>
                @endforeach
                {{-- <li class="nav-item"><a class="nav-link" href="{{ url('shop') }}">Shop</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('unseen-wins') }}">Unseen Wins</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url("coloring-book") }}">Coloring Book</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('contact-us') }}">Contact us</a></li> --}}
            </ul>
        </div>
    </div>
</nav>

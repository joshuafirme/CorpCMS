<!-- Navbar-->
@php
    $user = request()->user();
@endphp
<nav class="navbar navbar-marketing navbar-expand-lg bg-white navbar-light">
    <div class="container px-5">
        <a class="navbar-brand" href="/"><img style="width: 80px; height:auto;" class="img-fluid rounded"
                src="{{ asset(settings()->logo) }}" alt="logo"></a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i
                data-feather="menu"></i></button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto me-lg-5">
                <li class="nav-item"><a class="nav-link" href="/">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('news') }}">News</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('gallery') }}">Gallery</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('about-us') }}">About us</a></li>
                <li class="nav-item"><a class="nav-link" href="{{ url('contact-us') }}">Contact us</a></li>
            </ul>
        </div>
    </div>
</nav>

@extends('app._partials.app')

@section('content')
    {{-- @include('app._partials.hero-banner') --}}

    <section class="splide" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach ($sliders as $slider)
                    <li class="splide__slide">
                        <img style="width: 100%;height: 600px; object-fit: cover;" src="{{ asset($slider->image) }}" alt="{{ $slider->title }}">
                        <div class="image-title">{{ $slider->title }}</div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    <section class="bg-white py-5">
        <div class="container py-5">
            <h1 class="text-center mb-5 text-primary">Latest News</h1>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($news as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm">
                            @if ($item->image)
                                @php
                                    $mime = mime_content_type($item->image);
                                @endphp
                                @if (str_contains($mime, 'video/'))
                                    <video class="card-img-top" width="100%" height="300px" controls>
                                        <source src="{{ asset($item->image) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                @else
                                    <img src="{{ $item->image }}" style="object-fit: cover; height: 300px;"
                                        class="card-img-top" alt="{{ $item->title }}">
                                @endif
                            @endif
                            <div class="card-body">
                                <h3 class="card-title mt-0"><a href="{{ url("news/$item->slug") }}">{{ $item->title }}</a>
                                </h3>
                                <p class="text-muted mb-2"><small>Published on:
                                        {{ Utils::formatDate($item->date_published) }}</small></p>
                                <div class="lh-lg">{!! strlen($item->content) > 200 ? substr($item->content, 0, 199) . '...' : $item->content !!}</div>
                                <a href="{{ url("news/$item->slug") }}" class="btn btn-primary">Read More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <section class="bg-white py-5">
        <div class="svg-border-waves text-primary">
            <!-- Wave SVG Border-->
            <svg class="wave" style="pointer-events: none" fill="currentColor" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75">
                <defs>
                    <style>
                        .a {
                            fill: none;
                        }

                        .b {
                            clip-path: url(#a);
                        }

                        .d {
                            opacity: 0.5;
                            isolation: isolate;
                        }
                    </style>
                </defs>
                <clipPath id="a">
                    <rect class="a" width="1920" height="75"></rect>
                </clipPath>
                <g class="b">
                    <path class="c"
                        d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150">
                    </path>
                </g>
            </svg>
        </div>
    </section>
@endsection

@section('styles')
    <style>
        .splide__slide img {
            background-color: {{ settings()->primary_color }} !important;
        }
    </style>
@endsection

@section('scripts')
    <script src="{{ asset('libs/js/splide.min.js') }}"></script>

    <script>
        $(function() {
            var splide = new Splide('.splide', {
                type: 'loop',
                rewind: true,
                pagination: true, // Enable pagination
                arrows: false,
                autoplay: true,
                interval: 3000,
                speed: 1000,
            });
            splide.mount();
        })
    </script>
@endsection

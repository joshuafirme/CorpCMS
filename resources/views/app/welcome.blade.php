@extends('app._partials.app')

@section('content')

    <style>
        .slide-image-wrapper {
            position: relative;
            height: 60vh;
            /* reduced from 80vh */
            overflow: hidden;
        }

        .slide-image-wrapper img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-title {
            position: absolute;
            bottom: 20px;
            left: 30px;
            color: white;
            font-size: 1.8rem;
            background: rgba(0, 0, 0, 0.5);
            padding: 8px 16px;
            border-radius: 6px;
        }
    </style>

    <section class="splide" aria-label="Splide Basic HTML Example">
        <div class="splide__track">
            <ul class="splide__list">
                @foreach ($sliders as $slider)
                    <li class="splide__slide">
                        <div class="slide-image-wrapper">
                            <img src="{{ asset($slider->image) }}" alt="Slide" />
                            <div class="image-title">{{ $slider->title }}</div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>


    {{-- BOOK INTRO SECTION --}}


    <section class="bg-light py-5">
        <div class="container px-4 px-md-5">
            <div class="row align-items-center">
                <div class="col-md-6 mb-4 mb-md-0">
                    @if (isset($data->content))
                        <div class="mt-3 lh-lg">
                            {!! $data->content !!}
                        </div>
                    @endif
                </div>
                <div class="col-md-6 text-center">
                    <img src="{{ $data->cover_img }}" alt="Coach Xavier and team" class="img-fluid rounded shadow-sm"
                        style="max-height: 360px; object-fit: cover; width: 100%;">
                </div>
            </div>
        </div>
    </section>



    {{-- BLOG LISTING SECTION --}}
    <section class="bg-light py-5">
        <div class="container">
            <h2 class="text-dark mb-4">Latest Articles</h2>
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach ($articles as $item)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            {{-- Preserve image or video --}}
                            @if ($item->image)

                                @if ($item->image)
                                    @php
                                        $cover_img = $item && isset($item->image) ? $item->image : '';
                                    @endphp
                                    @if ($cover_img)
                                        <img width="100%" class="card-img-top" id="cover_img"  src="{{ asset('storage/' . $cover_img) }}"
                                            style="height: 250px; object-fit: cover;" />
                                    @endif
                                @endif
                            @endif

                            <div class="card-body d-flex flex-column">
                                <h3 class="card-title">
                                    <a href="{{ url("article/$item->slug") }}" class="text-dark text-decoration-none">
                                        {{ $item->title }}
                                    </a>
                                </h3>
                                <p class="text-muted small mb-2">
                                    Published on {{ Utils::formatDate($item->created_at) }}
                                </p>
                                <p class="card-text">
                                    {!! strlen($item->content) > 200 ? substr($item->content, 0, 199) . '...' : $item->content !!}
                                </p>
                                <div class="mt-auto">
                                    <a href="{{ url("article/$item->slug") }}" class="text-primary fw-bold">Read More →</a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>



    {{-- NEWSLETTER SECTION --}}
    <section id="newsletter" class="bg-white py-5 border-top">
        <div class="container text-center">
            <h2 class="fw-bold">Join the Newsletter</h2>
            <p class="text-muted">Get fresh blog updates every week. No spam. Just value.</p>
            <form action="/subscribe" method="post"
                class="d-flex flex-column flex-md-row justify-content-center gap-2 mt-3">
                <input type="email" name="email" class="form-control w-100 w-md-auto" placeholder="you@example.com"
                    required>
                <button type="submit" class="btn btn-primary">Subscribe</button>
            </form>
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

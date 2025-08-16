@extends('app._partials.app')

@section('content')

{{-- BOOK INTRO SECTION --}}
<section class="bg-primary text-white py-5">
  <div class="container px-4 px-md-5" style="margin-bottom: 150px">
    <div class="row align-items-center justify-content-center">

      {{-- Book Image --}}
      <div class="col-md-5 text-center mb-4 mb-md-0">
        <img src="{{ asset($data->cover_img) }}"
             alt="{{ $data->title }}"
             class="img-fluid rounded shadow-sm" style="max-height: 360px;">
      </div>

      {{-- Book Description --}}
      <div class="col-md-6 text-white">
        <h2 class="display-5 fw-bold">{{ $data->title }}</h2>
        {!! $data->content !!}
      </div>
    </div>
  </div>
</section>


{{-- NEWSLETTER SECTION --}}
<section id="newsletter" class="bg-white py-5 border-top">
    <div class="container text-center">
        <h2 class="fw-bold">Join the Newsletter</h2>
        <p class="text-muted">Get fresh blog updates every week. No spam. Just value.</p>
        <form action="/subscribe" method="post" class="d-flex flex-column flex-md-row justify-content-center gap-2 mt-3">
            <input type="email" name="email" class="form-control w-100 w-md-auto" placeholder="you@example.com" required>
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

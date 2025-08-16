@extends('app._partials.app')

@section('content')

{{-- BOOK INTRO SECTION --}}
<section class="bg-primary text-white py-5">
  <div class="container px-4 px-md-5">
    <div class="row align-items-center justify-content-center">

      {{-- Book Image --}}
      <div class="col-md-5 text-center mb-4 mb-md-0">
        <img src="https://placehold.co/300x400?text=Coloring+Book+Cover"
             alt="Coloring Book Coming Soon"
             class="img-fluid rounded shadow-sm" style="max-height: 360px;">
        <div class="mt-3">
          <span class="badge bg-warning text-dark fs-6 px-3 py-2 rounded-pill">🎉 Coming Soon</span>
        </div>
      </div>

      {{-- Book Description --}}
      <div class="col-md-6 text-white">
        <h2 class="display-5 fw-bold">[Coloring Book Title]</h2>
        <h4 class="mb-3">by Coach Xavier</h4>
        <p class="lead mb-4">
          A creative and values-driven coloring book for kids and young athletes.
        </p>
        <p class="fs-5 mb-4">
          Let young minds explore meaningful lessons through fun and engaging illustrations.
          This upcoming coloring book is filled with inspiring messages about teamwork, effort, and character.
        </p>
        <a href="#inquiry-form" class="btn btn-light btn-lg fw-semibold disabled" aria-disabled="true">
          🚧 Pre-order Coming Soon
        </a>
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

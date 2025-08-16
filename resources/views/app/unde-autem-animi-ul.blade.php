@extends('app._partials.app')

@section('content')

<section class="bg-dark text-white py-5">
  <div class="container px-4 px-md-5">
    <div class="row align-items-center justify-content-between g-5">

      {{-- Book Image in Banner --}}
      <div class="col-md-5 text-center">
        <img src="https://m.media-amazon.com/images/I/71yI9JssjYL._SY522_.jpg"
             alt="Unseen Wins Book Cover"
             class="img-fluid rounded shadow-lg" style="max-height: 380px;">
      </div>

      {{-- Book Description --}}
      <div class="col-md-6 text-white">
        <h2 class="display-5 fw-bold">Unseen Wins</h2>
        <h4 class="mb-3">by Coach Xavier Nunag</h4>
        <p class="lead mb-4">
          For Parents and Coaches Who Still Believe in Doing It Right.
        </p>
        <p class="fs-5 mb-4">
          A heartfelt book for those who believe success is measured not by trophies, but by values—discipline, humility, and effort. <strong>Unseen Wins</strong> is a reminder that the real victories are the ones that happen off the scoreboard.
        </p>
        <a href="https://www.amazon.com/Unseen-Wins-Parents-Coaches-Believe-ebook/dp/B0F9XBS2QB"
           class="btn btn-light btn-lg fw-semibold" target="_blank">
          📘 Order on Amazon
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Book Highlights Section -->
<section class="bg-light py-5">
  <div class="container px-4 px-md-5">
    <h3 class="text-primary fw-bold text-center mb-4">Inside the Book</h3>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="p-4 bg-white shadow-sm rounded h-100">
          <h5 class="fw-semibold mb-2">🏅 Chapter 1: The Power of Practice</h5>
          <p class="text-muted mb-0">Why showing up matters more than winning. The foundation of discipline starts at home and on the practice field.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white shadow-sm rounded h-100">
          <h5 class="fw-semibold mb-2">🤝 Chapter 5: Beyond the Game</h5>
          <p class="text-muted mb-0">How coaches and parents build character for life, not just for sports.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="p-4 bg-white shadow-sm rounded h-100">
          <h5 class="fw-semibold mb-2">💡 Timeless Lessons</h5>
          <p class="text-muted mb-0">Packed with quotes, values, and experiences that inspire young athletes and guide the adults who lead them.</p>
        </div>
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
                pagination: true,
                arrows: false,
                autoplay: true,
                interval: 3000,
                speed: 1000,
            });
            splide.mount();
        })
    </script>
@endsection
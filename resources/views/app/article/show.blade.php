@extends('app._partials.app')

@section('content')
    <section class="bg-light py-5">
        <div class="container px-4 px-md-5">
            {{-- Image on Top --}}
            <div class="row justify-content-center mb-4">
                <div class="col-12 text-center">
                    @php
                        $cover_img = isset($article->image) ? $article->image : '';
                    @endphp

                    @if ($cover_img)
                        <img src="{{ asset('storage/' . $cover_img) }}"
                             alt="Article cover"
                             class="img-fluid rounded shadow-sm mb-3"
                             style="max-height: 400px; object-fit: cover; width: 100%;">
                    @endif
                    <p> Published on {{ Utils::formatDate($article->created_at) }} </p>

                </div>
            </div>

            {{-- Content Below --}}
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="mt-3 lh-lg">
                        {!! $article->content !!}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

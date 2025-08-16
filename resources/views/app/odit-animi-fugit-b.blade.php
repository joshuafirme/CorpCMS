@extends('app._partials.app')

@section('content')

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
                    <img src="{{ $data->cover_img }}"
                        alt="Coach Xavier and team" class="img-fluid rounded shadow-sm"
                        style="max-height: 360px; object-fit: cover; width: 100%;">
                </div>
            </div>
        </div>
    </section>

@endsection


@section('scripts')

@endsection

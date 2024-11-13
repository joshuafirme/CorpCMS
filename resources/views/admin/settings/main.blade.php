@extends('admin._partials.app')
@php
    $title = 'General Settings';
    $color = "#000";
@endphp
@section('title', $title)

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            {{ $title }}
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">

                @include('admin._partials.alerts')

                <form action="{{ route('settings.store') }}" class="row" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="col-12 row">
                        <div class="col-md-4 b-3">
                            <label class="form-label small">App name</label>
                            <input type="text" class="form-control" name="app_name"
                                value="{{ $data ? $data->app_name : '' }}" required>
                        </div>
                        <div class="col-md-4 b-3">
                            <label for="formFile" class="form-label">Logo</label>
                            <input class="form-control file-upload" name="logo" type="file" id="formFile"
                                accept="image/png, image/gif, image/jpeg">
                            <div class="overflow-auto img-container mt-2">
                                @php
                                    $logo = $data && isset($data->logo) ? $data->logo : ''
                                @endphp
                                <img width="100%" class="img-preview" id="logo" src="{{ asset($logo) }}"
                                    style="max-width: 250px; object-fit: cover;" />
                            </div>
                        </div>
                    </div>

                    <div class="col-12 row mt-3">
                        <div class="col-md-4 b-3">
                            <label class="form-label small">Primary color</label>
                            <input type="text" class="form-control" name="primary_color" value="{{ $data ? $data->primary_color : '' }}">
                        </div>
                        <div class="col-md-4 b-3">
                            <label class="form-label small">Secondary color</label>
                            <input type="text" class="form-control" name="secondary_color" value="{{ $data ? $data->secondary_color : '' }}">
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('js/custom/helper.js') }}"></script>
    <script>
        $(function() {
            $(".file-upload").change(function() {
                const file = this.files[0];
                let __this = $(this);
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function(event) {
                        __this.parent().parent().find(".img-preview")
                            .attr("src", event.target.result);
                    };
                    reader.readAsDataURL(file);
                }
            });
        })
    </script>
@endsection

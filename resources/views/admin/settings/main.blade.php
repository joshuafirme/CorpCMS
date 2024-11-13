@extends('admin._partials.app')
@php
    $title = 'General Settings';
    $color = '#000';
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
    <style>
        .form-color {
            border: none;
            width: 40px;
            height: 100%;
            padding: 0;
            cursor: pointer;
        }

        .form-color::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .form-color::-webkit-color-swatch {
            border: none;
        }
    </style>
    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">

                @include('admin._partials.alerts')

                <form action="{{ route('settings.store') }}" class="row" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="container-floating-label col-12">
                        <div class="border-container position-relative shadow-sm">
                            <label class="floating-label">App information</label>
                            <div class="content p-4 row">

                                <div class="col-md-4 b-3">
                                    <label class="form-label small">App name</label>
                                    <input type="text" class="form-control" name="app_name"
                                        value="{{ $data ? $data->app_name : '' }}" required>
                                </div>
                                <div class="col-md-4 b-3">
                                    <label class="form-label small">Meta description</label>
                                    <textarea rows="4" type="text" class="form-control" name="meta_description">{{ isset($data->meta_description) ? $data->meta_description : '' }}</textarea>
                                </div>
                                <div class="col-md-4 b-3">
                                    <label for="formFile" class="form-label">Logo</label>
                                    <input class="form-control file-upload" name="logo" type="file" id="formFile"
                                        accept="image/png, image/gif, image/jpeg">
                                    <div class="overflow-auto img-container mt-2">
                                        @php
                                            $logo = $data && isset($data->logo) ? $data->logo : '';
                                        @endphp
                                        <img width="100%" class="img-preview" id="logo" src="{{ asset($logo) }}"
                                            style="max-width: 50px; object-fit: cover;" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-floating-label col-md-6 mt-5">
                        <div class="border-container position-relative shadow-sm">
                            <label class="floating-label">Theme</label>
                            <div class="content p-4 row">
                                <div class="col-md-6 b-3">
                                    <label class="form-label small">Primary color</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0">
                                            <input type="color" class="form-color" id="primary-color-picker"
                                                value="{{ $data ? $data->primary_color : '' }}">
                                        </span>
                                        <input type="text" class="form-control" id="primary_color" placeholder="#FFFFFF"
                                            name="primary_color" value="{{ $data ? $data->primary_color : '' }}">
                                    </div>
                                </div>
                                <div class="col-md-6 b-3">
                                    <label class="form-label small">Secondary color</label>
                                    <div class="input-group">
                                        <span class="input-group-text p-0">
                                            <input type="color" class="form-color" id="secondary-color-picker"
                                                value="{{ $data ? $data->secondary_color : '' }}">
                                        </span>
                                        <input type="text" class="form-control" id="secondary_color"
                                            placeholder="#FFFFFF" name="secondary_color"
                                            value="{{ $data ? $data->secondary_color : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="container-floating-label col-md-6 mt-5">
                        <div class="border-container position-relative shadow-sm">
                            <label class="floating-label">Social links</label>
                            <div class="content p-4 row">
                                <div class="col-mb-6 mb-3">
                                    <label class="form-label small">Facebook</label>
                                    <input type="text" class="form-control" name="facebook"
                                        value="{{ isset($data->facebook) ? $data->facebook : '' }}">
                                </div>
                                <div class="col-mb-6 mb-3">
                                    <label class="form-label small">Instagram</label>
                                    <input type="text" class="form-control" name="instagram"
                                        value="{{ isset($data->instagram) ? $data->instagram : '' }}">
                                </div>
                                <div class="col-mb-6 mb-3">
                                    <label class="form-label small">Linkedin</label>
                                    <input type="text" class="form-control" name="linkedin"
                                        value="{{ isset($data->linkedin) ? $data->linkedin : '' }}">
                                </div>
                                <div class="col-mb-6 mb-3">
                                    <label class="form-label small">X (Twitter)</label>
                                    <input type="text" class="form-control" name="twitter"
                                        value="{{ isset($data->twitter) ? $data->twitter : '' }}">
                                </div>
                                <div class="col-mb-6 mb-3">
                                    <label class="form-label small">TikTok</label>
                                    <input type="text" class="form-control" name="tiktok"
                                        value="{{ isset($data->tiktok) ? $data->tiktok : '' }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-5">
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

            $(document).ready(function() {
                $('#primary-color-picker').on('input', function() {
                    // Get the selected color value
                    let color = $(this).val();
                    // Set the color value in the text input
                    $('#primary_color').val(color);
                });
            });

            $(document).ready(function() {
                $('#secondary-color-picker').on('input', function() {
                    // Get the selected color value
                    let color = $(this).val();
                    // Set the color value in the text input
                    $('#secondary_color').val(color);
                });
            });
        })
    </script>
@endsection

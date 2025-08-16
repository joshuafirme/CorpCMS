@extends('admin._partials.app')

@section('title', $page_title)

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            {{ $page_title }}
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

                <form action="{{ url("admin/page-content/$type") }}" class="row" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="slug" value="{{ $type }}">


                    <div class="col-md-12 mb-3">
                        <label class="form-label small">Title</label>
                        <input type="text" class="form-control" name="title" value="{{ isset($page->title) ? $page->title : '' }}">
                    </div>

                    @if ($type == 'home')
                        <div class="col-md-12 mb-3">
                            <label for="formFile" class="form-label small">Cover image</label>
                            <input class="form-control file-upload" name="cover_img" type="file" id="formFile"
                                accept="image/png, image/gif, image/jpeg">
                            <div class="overflow-auto img-container mt-2">
                                @php
                                    $cover_img = $page && isset($page->cover_img) ? $page->cover_img : '';
                                @endphp
                                @if ($cover_img)
                                    <img width="100%" class="img-preview" id="cover_img" src="{{ asset($cover_img) }}"
                                        style="max-width: 250px; object-fit: cover;" />
                                @endif
                            </div>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label small">Content</label>
                            <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($page->content) ? $page->content : '' }}</textarea>
                        </div>
                    @elseif ($type == 'about-us')
                        <div class="col-md-12 mb-3">
                            <label for="formFile" class="form-label small">Cover image</label>
                            <input class="form-control file-upload" name="cover_img" type="file" id="formFile"
                                accept="image/png, image/gif, image/jpeg">
                            <div class="overflow-auto img-container mt-2">
                                @php
                                    $cover_img = $page && isset($page->cover_img) ? $page->cover_img : '';
                                @endphp
                                @if ($cover_img)
                                    <img width="100%" class="img-preview" id="cover_img" src="{{ asset($cover_img) }}"
                                        style="max-width: 250px; object-fit: cover;" />
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="show_cover_img"
                                    id="show_cover_img"
                                    {{ isset($page->show_cover_img) && $page->show_cover_img == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_cover_img">Show cover image</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label small">Content</label>
                            <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($page->content) ? $page->content : '' }}</textarea>
                        </div>
                    @elseif ($type == 'contact-us')
                        <div class="col-md-12 mb-3">
                            <label class="form-label small">Content</label>
                            <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($page->content) ? $page->content : '' }}</textarea>
                        </div>
                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="show_content"
                                    id="show_content"
                                    {{ isset($page->show_content) && $page->show_content == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_content">Show content</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="page_status"
                                    id="page_status"
                                    {{ isset($page->page_status) && $page->page_status == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="page_status">Published</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="show_contact_form"
                                    id="show_contact_form"
                                    {{ isset($page->show_contact_form) && $page->show_contact_form == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_contact_form">Show contact form</label>
                            </div>
                        </div>
                    @elseif ($type == 'privacy-policy' || $type == 'terms-of-service')
                        <div class="col-md-12 mb-3">
                            <label class="form-label small">Content</label>
                            <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($page->content) ? $page->content : '' }}</textarea>
                        </div>
                    @else
                        <div class="col-md-12 mb-3">
                            <label for="formFile" class="form-label small">Cover image</label>
                            <input class="form-control file-upload" name="cover_img" type="file" id="formFile"
                                accept="image/png, image/gif, image/jpeg">
                            <div class="overflow-auto img-container mt-2">
                                @php
                                    $cover_img = $page && isset($page->cover_img) ? $page->cover_img : '';
                                @endphp
                                @if ($cover_img)
                                    <img width="100%" class="img-preview" id="cover_img"
                                        src="{{ asset($cover_img) }}" style="max-width: 250px; object-fit: cover;" />
                                @endif
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label small">Content</label>
                            <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($page->content) ? $page->content : '' }}</textarea>
                        </div>

                        {{-- <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="show_contact_form"
                                    id="show_contact_form"
                                    {{ isset($page->show_contact_form) && $page->show_contact_form == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_contact_form">Show contact
                                    form</label>
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="show_content"
                                    id="show_content"
                                    {{ isset($page->show_content) && $page->show_content == 'on' ? 'checked' : '' }}>
                                <label class="form-check-label" for="show_content">Show content</label>
                            </div>
                        </div> --}}



                        <div class="col-md-12 mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch" name="page_status"
                                    id="page_status"
                                    {{ isset($page->is_published) && $page->is_published ? 'checked' : '' }}>
                                <label class="form-check-label" for="page_status">Published {{ $page->is_published }}</label>
                            </div>
                        </div>
                    @endif


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
    <script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>

    <script>
        $(function() {
            if ($('#editor').length > 0) {
                ClassicEditor
                    .create(document.querySelector('#editor'), {
                        ckfinder: {
                            uploadUrl: '{{ env('APP_URL') }}ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                        }
                    })
                    .then(editor => {
                        // Store the editor instance
                        editorInstance = editor;
                    })
                    .catch(error => {
                        console.error(error);
                    });
            }
        })
    </script>

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

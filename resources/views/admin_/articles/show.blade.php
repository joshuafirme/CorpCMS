@extends('admin._partials.app')

@php
    $page_title = 'Articles';
@endphp

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

                <form action="{{ route('articles.update', $article->id) }}" class="row" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <input type="hidden" name="slug" value="{{ $article->slug }}">

                    <div class="col-md-12 mb-3">
                        <label for="formFile" class="form-label small">Cover image</label>
                        <input class="form-control file-upload" name="update_image" type="file" id="formFile"
                            accept="image/png, image/gif, image/jpeg">
                        <div class="overflow-auto img-container mt-2">
                            @php
                                $cover_img = $article ?? '' && isset($article->image) ? $article->image : '';
                            @endphp
                            @if ($cover_img)
                                <img width="100%" class="img-preview" id="cover_img"
                                    src="{{ asset('storage/' . $cover_img) }}"
                                    style="max-width: 250px; object-fit: cover;" />
                            @endif
                        </div>
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label small">Title</label>
                        <input type="text" name="title" class="form-control" value="{{ $article->title }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label small">Slug</label>
                        <input type="text" name="slug" class="form-control" value="{{ $article->slug }}">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label small">Content</label>
                        <textarea class="form-control" id="editor" name="content" rows="10">{{ isset($article->content) ? $article->content : '' }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" role="switch" name="status" id="status"
                                {{ isset($article->status) && $article->status == 'on' ? 'checked' : '' }}>
                            <label class="form-check-label" for="status">Published</label>
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

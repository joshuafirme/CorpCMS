@extends('admin._partials.app')
@php
    $title = 'News';
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
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="#" data-bs-toggle="modal"
                            data-bs-target="#updateModal">
                            <i class="me-1" data-feather="user-plus"></i>
                            Add {{ $title }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main page content-->
    <div class="container-fluid px-4">
        <div class="card">
            <div class="card-body">
                <table id="datatablesSimple" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Meta Description</th>
                            <th>Media</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) > 0)
                            @foreach ($data as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->title }}</td>
                                    <td class="wrap">
                                        <div>{{ $item->meta_description }}</div>
                                    </td>
                                    <td>
                                        @if ($item->image)
                                            @php
                                                $mime = mime_content_type($item->image);
                                            @endphp
                                            @if (str_contains($mime, 'video/'))
                                                <video width="220" height="140" controls>
                                                    <source src="{{ asset($item->image) }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <a target="_blank" href="{{ url($item->image) }}"><img
                                                        src="{{ asset($item->image) }}" width="220px" alt=""></a>
                                            @endif
                                        @endif
                                    </td>
                                    <td>
                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-edit"
                                            data-item="{{ $item }}" data-bs-toggle="modal"
                                            data-bs-target="#updateModal">
                                            <i class="fa fa-edit"></i>
                                        </a>

                                        <a class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-delete"
                                            data-id="{{ $item->id }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning"><small>No data found.</small></div>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
                @php
                    echo $data->links('pagination::bootstrap-4');
                @endphp
            </div>
        </div>
    </div>
    @include('admin.news.modals')
@endsection

@section('scripts')
    <script src="{{ asset('js/custom/helper.js') }}"></script>

    <script src="https://cdn.ckeditor.com/ckeditor5/35.2.0/classic/ckeditor.js"></script>

    @include('admin.news.script')

@endsection

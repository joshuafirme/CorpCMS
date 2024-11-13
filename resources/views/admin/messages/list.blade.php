@extends('admin._partials.app')
@php
    $title = 'Messages';
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
                <table id="datatablesSimple" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Message</th>
                            <th>Sent at</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if (count($data) > 0)
                            @foreach ($data as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        <div>{{ $item->email }}</div>
                                        <a href="mailto:{{ $item->email }}">Send email</a>
                                    </td>
                                    <td class="wrap">
                                        <div>{{ $item->message }}</div>
                                    </td>
                                    <td>
                                        @if ($item->image)
                                            <a target="_blank" href="{{ url($item->image) }}"><img
                                                    src="{{ asset($item->image) }}" width="150px" alt=""></a>
                                        @endif
                                    </td>
                                    <td>{{ $item->created_at }}</td>
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

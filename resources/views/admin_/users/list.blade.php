@extends('admin._partials.app')
@php
    $title = 'System Users';
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
                            {{ $title }} List
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primar3 btn-edit" href="#" data-bs-toggle="modal"
                            data-bs-target="#updateModal">
                            <i class="me-1" data-feather="user-plus"></i>
                            Add New {{ $title }}
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
                <div class="row">
                    <form action="{{ url('/users') }}" method="get" class="mt-2 ml-auto col-md-4" autocomplete="off"
                        style="margin-left: auto">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input-search" name="key"
                                placeholder="Search by name, or email"
                                value="{{ isset($_GET['key']) ? $_GET['key'] : '' }}">
                            <button class="btn btn-primary" type="submit" type="button" id="button-addon2"><i
                                    class="fa fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <table id="datatablesSimple" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @if (count($users) > 15)
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    @endif
                    <tbody>
                        @if (count($users) > 0)
                            @foreach ($users as $item)
                                <tr id="{{ $item->id }}">
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->role }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="badge bg-green-soft text-green">Active</span>
                                        @elseif ($item->status == 0)
                                            <span class="badge bg-red-soft text-red">Inactive</span>
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
                                    <div class="alert alert-warning"><small>No users found.</small></div>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

        </div>
        @php
            echo $users->appends(request()->query())->links('pagination::bootstrap-4');
        @endphp
    </div>
    </div>
    @include('admin.users.modals')
@endsection

@section('scripts')
    @include('admin.users.script')
@endsection

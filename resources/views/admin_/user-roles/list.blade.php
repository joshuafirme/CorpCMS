@extends('admin._partials.app')
@section('title', 'Roles & Permission')

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="user"></i></div>
                            Users List
                        </h1>
                    </div>
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary" href="#" data-bs-toggle="modal"
                            data-bs-target="#roleModal">
                            <i class="me-1" data-feather="user-plus"></i>
                            Add new role
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
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Name</th>
                                <th scope="col">Permissions</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (count($user_roles) > 0)
                                @foreach ($user_roles as $item)
                                    <tr id="{{ $item->id }}">
                                        <td>{{ $item->name }}</td>
                                        <td>
                                            @php
                                                if ($item->permissions != 'null' && $item->permissions) {
                                                    $permissions = json_decode($item->permissions);
                                                    foreach ($permissions as $value) {
                                                        echo '<span class="badge m-1 rounded-pill bg-success">' .
                                                            Utils::decodeSlug($value) .
                                                            '</span>';
                                                    }
                                                }

                                            @endphp
                                        </td>
                                        <td>
                                            <a class=" btn-datatable btn-icon btn-transparent-dark me-2 btn-edit open-modal"
                                                modal-type="update" href="#" data-info="{{ json_encode($item) }} "><i
                                                    class="fa fa-edit"></i>
                                            </a>
                                            <a class=" btn-datatable btn-icon btn-transparent-dark me-2 btn-danger btn-delete"
                                                href="#"data-id="{{ $item->id }}">
                                                <i class="fa-regular fa-trash-can"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="12">
                                        <div class="alert alert-primary">No requests found.</div>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
                @php
                    echo $user_roles->links('pagination::bootstrap-4');
                @endphp
            </div>
        </div>
    </div>
    @include('admin.user-roles.modals')
@endsection

@section('scripts')

    @include('admin.user-roles.script')
@endsection

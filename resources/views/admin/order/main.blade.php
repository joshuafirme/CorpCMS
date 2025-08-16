@extends('admin._partials.app')
@php
    $title = 'Orders';
    $color = '#000';
@endphp
@section('orders', $title)

@section('content')
    <header class="page-header page-header-compact page-header-light border-bottom bg-white mb-4">
        <div class="container-fluid px-4">
            <div class="page-header-content">
                <div class="row align-items-center justify-content-between pt-3">
                    <div class="col-auto mb-3">
                        <h1 class="page-header-title">
                            <div class="page-header-icon"><i data-feather="box"></i></div>
                            {{ $title }}
                        </h1>
                    </div>
                    {{-- <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primar3 btn-edit" href="#" data-bs-toggle="modal"
                            data-bs-target="#updateModal">
                            <i class="me-1" data-feather="user-plus"></i>
                            Add New {{ $title }}
                        </a>
                    </div> --}}
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
                <table id="productTable" class="table table-hover table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Slug</th>
                            <th>Qty</th>
                            <th>Description</th>
                            <th>Notes</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @if (count($orders ?? []) > 15)
                        <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Qty</th>
                                <th>Address</th>
                                <th>Notes</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    @endif
                    <tbody>
                        @if (count($orders ?? []) > 0)
                            @foreach ($orders as $item)
                                <tr>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->email }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>{{ $item->address }}</td>
                                    <td>{{ $item->note }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="badge bg-green-soft text-green">Active</span>
                                        @elseif ($item->status == 0)
                                            <span class="badge bg-red-soft text-red">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button
                                            class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-edit view-order"
                                            id="ordersTable" data-id="{{ $item->id }}" data-name="{{ $item->name }}"
                                            data-email="{{ $item->email }}" data-book="{{ $item->book }}"
                                            data-address="{{ $item->address }}" data-note="{{ $item->note }}"
                                            data-image="{{ $item->payment_proof_path ?? '' }}">
                                            <i class="fa fa-edit"></i> </button>
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
                                    <div class="alert alert-warning"><small>No order found.</small></div>
                                </td>
                            </tr>
                        @endif

                    </tbody>
                </table>
            </div>

        </div>
        @php
            echo isset($users) ?? $users->appends(request()->query())->links('pagination::bootstrap-4');
        @endphp
    </div>
    </div>
    @include('admin.users.modals')

@endsection

@section('scripts')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('js/custom/helper.js') }}"></script>
    <script>
        $(function() {
            // preview modal form
            $('.view-btn').on('click', function() {
                let name = $(this).data('name');
                let email = $(this).data('email');
                let status = $(this).data('status');

                // Populate modal fields
                $('#updateModal input[name="name"]').val(name);
                $('#updateModal input[name="email"]').val(email);
                $('#updateModal select[name="status"]').val(status);
            });

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

        $(document).on('click', '.view-order', function() {

            const name = $(this).data('name');
            const id = $(this).data('id');
            const email = $(this).data('email');
            const book = $(this).data('book');
            const address = $(this).data('address');
            const note = $(this).data('note');
            const image = $(this).data('image');

            $('#name').val(name);
            $('#email').val(email);
            $('#book').val(book);
            $('#address').val(address);
            $('#note').val(note);
            $('#orderId').val(id);

            const baseUrl = window.location.origin; // e.g., https://example.com
            if (image && (image.endsWith('.jpg') || image.endsWith('.jpeg') || image.endsWith('.png'))) {
                const fullImagePath = baseUrl + '/' + image.replace(/^\/+/, ''); // Ensure no double slashes
                $('#proofImage').attr('src', fullImagePath).show();
                $('#noImageText').hide();
            } else {
                $('#proofImage').hide();
                $('#noImageText').show();
            }

            const modal = new bootstrap.Modal(document.getElementById('viewOrderModal'));
            modal.show();

        });

        $('#approveBtn').click(function() {
            let orderId = $('#orderId').val();

            if (!orderId) {
                alert('Order ID is missing.');
                return;
            }

            $.ajax({
                url: 'orders/approve/' + orderId,
                method: 'POST',
                data: {
                    status: 1
                },
                success: function(response) {
                    alert('Order approved successfully.');
                    $('#updateModal').modal('hide');
                    // Optionally reload table or update row
                    location.reload();
                },
                error: function(xhr) {
                    alert('Failed to approve order.');
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {

            $('#productTable').DataTable({
                pageLength: 10,
                order: [
                    [0, 'desc']
                ],
                responsive: true,
                language: {
                    search: "_INPUT_",
                    searchPlaceholder: "Search products..."
                }
            });

            $('#paymentProof').on('change', function() {
                const file = this.files[0];

                // Check if the file is an image
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        $('#previewImage').attr('src', e.target.result);
                        $('#previewContainer').show();
                    };
                    reader.readAsDataURL(file);
                } else {
                    $('#previewContainer').hide();
                    alert('Only image previews are supported in the form. PDFs will not preview.');
                }
            });


        });

        $(document).on('click', '.btn-delete', function() {
            let orderId = $(this).data('id');
            let row = $(this).closest('tr');

            if (!confirm('Are you sure you want to delete this order?')) {
                return;
            }

            $.ajax({
                url: '/admin/orders/' + orderId,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    alert(response.message || 'Order deleted successfully');
                    // Remove row from DataTable
                    $('#productTable').DataTable().row(row).remove().draw(false);
                },
                error: function(xhr) {
                    alert(xhr.responseJSON?.message || 'Failed to delete order.');
                }
            });
        });
    </script>
@endsection

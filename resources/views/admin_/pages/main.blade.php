@extends('admin._partials.app')
@php
    $title = 'Pages';
    $color = '#000';
@endphp
@section('Pages', $title)

@section('content')

    <!-- Add New Product Modal -->
    <!-- Create Page Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="createPageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="createPageLabel">Create New Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="createPageForm">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="title" class="form-label">Title</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="slug" class="form-label">Slug</label>
                                <input type="text" name="slug" class="form-control" required>
                            </div>

                            <div class="col-md-12">
                                <label for="content" class="form-label">Content</label>
                                <textarea name="content" class="form-control" rows="4"></textarea>
                            </div>

                            {{-- <div class="col-md-12">
                                <label for="meta_description" class="form-label">Meta Description</label>
                                <textarea name="meta_description" class="form-control" rows="2"></textarea>
                            </div> --}}

                            <div class="col-md-6">
                                <label for="is_published" class="form-label">Status</label>
                                <select name="is_published" class="form-select" required>
                                    <option value="1">Published</option>
                                    <option value="0" selected>Draft</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Create Page</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- End Modal --}}


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
                    <div class="col-12 col-xl-auto mb-3">
                        <a class="btn btn-sm btn-light text-primary btn-edit" href="#" data-bs-toggle="modal"
                            data-bs-target="#updateModal">
                            <i class="me-1" data-feather="user-plus"></i>
                            Add New {{ $title }}
                        </a>
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
                            <th>Title</th>
                            <th>Slug</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @if (count($product ?? []) > 15)
                        <tfoot>
                            <tr>
                                <th>Title</th>
                                <th>Slug</th>
                                <th>Description</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    @endif
                    <tbody>
                        @if (count($data ?? []) > 0)
                            @foreach ($data as $item)
                                <tr>
                                    <td>{{ $item->title ?? '' }}</td>
                                    <td>{{ $item->slug ?? '' }}</td>
                                    <td>{{ $item->description ?? '' }}</td>
                                    <td>
                                        @if ($item->is_published == 1)
                                            <span class="badge bg-green-soft text-green">Active</span>
                                        @elseif ($item->is_published == 0)
                                            <span class="badge bg-red-soft text-red">Inactive</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{-- <button class="btn btn-sm btn-info mb-2 btn-view-product">
                                            <i class="fa fa-edit"></i>
                                        </button> --}}
                                        <a href="{{ url('admin/page-content', Str::lower(Str::slug($item->slug))) }}" class="btn btn-sm btn-info" >View</a>
                                        <button type="button"
                                            class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-delete-product"
                                            data-id="{{ $item->id }}" data-name="{{ $item->title }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="12">
                                    <div class="alert alert-warning"><small>No Data found.</small></div>
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
    {{-- @include('admin.product.modals') --}}

    <!-- Product Modal -->
    {{-- <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true"> --}}

    <!-- View Product Modal -->
    <!-- View Page Modal -->
    <div class="modal fade" id="viewPageModal" tabindex="-1" aria-labelledby="viewPageLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewPageLabel">View Page</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="row g-3">
                        <input type="hidden" id="view_page_id">

                        <div class="col-md-6">
                            <label for="view_title" class="form-label">Title</label>
                            <input type="text" id="view_title" class="form-control" readonly>
                        </div>

                        <div class="col-md-6">
                            <label for="view_slug" class="form-label">Slug</label>
                            <input type="text" id="view_slug" class="form-control" readonly>
                        </div>

                        <div class="col-md-12">
                            <label for="view_content" class="form-label">Content</label>
                            <textarea id="view_content" class="form-control" rows="4" readonly></textarea>
                        </div>

                        <div class="col-md-12">
                            <label for="view_meta_description" class="form-label">Meta Description</label>
                            <textarea id="view_meta_description" class="form-control" rows="2" readonly></textarea>
                        </div>

                        <div class="col-md-6">
                            <label for="view_is_published" class="form-label">Status</label>
                            <input type="text" id="view_is_published" class="form-control" readonly>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteProductModal" tabindex="-1" aria-labelledby="deleteProductLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content p-3">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteProductLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete <strong id="deleteProductName"></strong>?</p>
                    <input type="hidden" id="deleteProductId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="confirmDeleteBtn" class="btn btn-danger">Delete</button>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('scripts')
    <script src="{{ asset('js/custom/helper.js') }}"></script>

    <script>
        $(document).ready(function() {



            $(document).on('click', '.btn-view-product', function() {
                const modal = $('#viewPageModal');

                const product = {
                    id: $(this).data('id'),
                    name: $(this).data('name') || '',
                    slug: $(this).data('slug') || '',
                    description: $(this).data('description') || '',
                    qty: $(this).data('qty') || 0,
                    status: $(this).data('status'),
                    price: $(this).data('price') || 0,
                    img: $(this).data('img') || ''
                };

                modal.find('#productId').val(product.id);
                modal.find('#product_name').val(product.name);
                modal.find('#product_slug').val(product.slug);
                modal.find('#qty').val(product.qty);
                modal.find('#status').val(product.status == 1 ? 'Active' : 'Inactive');
                modal.find('#product_price').val(product.price);
                modal.find('#product_description').val(product.description);

                if (product.img) {
                    modal.find('#product_img').attr('src', product.img).show();
                    modal.find('#noProductImageText').hide();
                } else {
                    modal.find('#product_img').hide();
                    modal.find('#noProductImageText').show();
                }

                bootstrap.Modal.getOrCreateInstance(modal[0]).show();
            });

            let deleteModal = new bootstrap.Modal(document.getElementById('deleteProductModal'));

            // 🧲 Trigger modal and populate data
            $('.btn-delete-product').on('click', function() {
                const productId = $(this).data('id');
                const productName = $(this).data('name');

                $('#deleteProductId').val(productId);
                $('#deleteProductName').text(productName);

                deleteModal.show();
            });

            // 🧼 Confirm Delete with AJAX
            $('#confirmDeleteBtn').on('click', function() {
                const pageId = $('#deleteProductId').val();

                $.ajax({
                    url: '{{ route("admin.pages.destroy", ":id") }}'.replace(':id', pageId),
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        deleteModal.hide();
                        // Optionally remove row or reload
                        location.reload(); // or $(`#row-${productId}`).remove();
                    },
                    error: function(xhr) {
                        alert('Failed to delete the product.');
                    }
                });
            });

        })



        $('#productForm').on('submit', function(e) {
            e.preventDefault();

            // Clear previous errors
            $('.is-invalid').removeClass('is-invalid');
            $('.invalid-feedback').remove();

            var formData = new FormData(this);

            console.log(formData);
            // return false;

            $.ajax({
                url: '/admin/products',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    // Optional: show success message
                    alert('Product added successfully.');

                    // Close modal
                    $('#updateModal').modal('hide');

                    // Reset form
                    $('#productForm')[0].reset();

                    // Optional: refresh product list
                    location.reload(); // Or dynamically update DOM
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            let input = $('[name="' + key + '"]');
                            input.addClass('is-invalid');
                            input.after('<div class="invalid-feedback">' + value[
                                0] + '</div>');
                        });
                    } else {
                        alert('An error occurred while saving the product.');
                    }
                }
            });
        });

        $('#createPageForm').on('submit', function(e) {
            e.preventDefault();

            let formData = new FormData(this);

            $.ajax({
                url: '{{ route('admin.pages.store') }}', // adjust route name as needed
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    location.reload();
                    alert('Page created successfully!');
                    $('#createPageModal').modal('hide');
                    $('#createPageForm')[0].reset();
                    // Optionally refresh table or DOM list
                },
                error: function(xhr) {
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;
                        let errorMsg = '';
                        Object.keys(errors).forEach(key => {
                            errorMsg += errors[key][0] + '\n';
                        });
                        alert(errorMsg);
                    } else {

                        alert('Something went wrongx.');
                    }
                }
            });
        });
    </script>
    <script>
        $(document).ready(function() {
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
    </script>
@endsection

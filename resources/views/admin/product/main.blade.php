@extends('admin._partials.app')
@php
    $title = 'Product';
    $color = '#000';
@endphp

@section('Product', $title)

@section('content')

    <!-- Add New Product Modal -->
    <div class="modal fade" id="updateModal" tabindex="-1" aria-labelledby="updateModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateModalLabel">Add New {{ $title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <form id="productForm" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="product_name" class="form-label">Product Name</label>
                                <input type="text" name="product_name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="product_slug" class="form-label">Slug</label>
                                <input type="text" name="product_slug" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label for="qty" class="form-label">Quantity</label>
                                <input type="number" name="qty" class="form-control" min="1" required>
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" class="form-select" required>
                                    <option value="1">Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                            <!-- Product Price -->
                            <div class="col-md-12">
                                <label class="form-label">Product price</label>
                                <input type="text" name="product_price" class="form-control" id="product_pricex">
                            </div>

                            <div class="col-12">
                                <label for="product_description" class="form-label">Product Description</label>
                                <textarea name="product_description" class="form-control" rows="3" required></textarea>
                            </div>

                            <div class="col-12">
                                <label for="product_img" class="form-label">Product Image</label>
                                <input type="file" name="product_img" class="form-control" accept="image/*" required>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Save Product</button>
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
                <table id="productTable" class="table table-hover table-bordered align-middle">

                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Product Slug</th>
                            <th>Qty</th>
                            <th>Product Image</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @if (count($product ?? []) > 15)
                        <tfoot>
                            <tr>
                                <th>Product Name</th>
                                <th>Product Slug</th>
                                <th>Qty</th>
                                <th>Product Image</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    @endif
                    <tbody>
                        @if (count($product ?? []) > 0)
                            @foreach ($product as $item)
                                <tr>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->product_slug }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->product_img }}</td>
                                    <td>
                                        @if ($item->status == 1)
                                            <span class="badge bg-green-soft text-green">Active</span>
                                        @elseif ($item->status == 0)
                                            <span class="badge bg-red-soft text-red">Inactive</span>
                                        @endif
                                    </td>
                                    <td>

                                        <button class="btn btn-sm btn-info mb-2 btn-view-product"
                                            data-id="{{ $item->id }}" data-name="{{ $item->product_name }}"
                                            data-slug="{{ $item->product_slug }}"
                                            data-description="{{ $item->product_description }}"
                                            data-qty="{{ $item->qty }}" data-status="{{ $item->status }}"
                                            data-product_price="{{ $item->product_price }}"
                                            data-img="{{ asset($item->product_img) }}" data-bs-toggle="modal"
                                            data-bs-target="#dynamicProductModal" data-bs-toggle="modal"
                                            data-bs-target="#viewOrderModal">
                                            <i class="fa fa-edit"></i>
                                        </button>
                                        <button type="button"
                                            class="btn btn-datatable btn-icon btn-transparent-dark me-2 btn-delete-product"
                                            data-id="{{ $item->id }}" data-name="{{ $item->product_name }}">
                                            <i class="fa-regular fa-trash-can"></i>
                                        </button>
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
    {{-- @include('admin.product.modals') --}}

    <!-- Product Modal -->
    {{-- <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true"> --}}
    <div class="modal fade" id="viewOrderModal" tabindex="-1" aria-labelledby="viewOrderLabel" aria-hidden="true">

        <div class="modal-dialog modal-lg">
            <div class="modal-content p-4">
                <div class="modal-header">
                    <h5 class="modal-title" id="viewOrderLabel">Product Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="viewOrderForm">
                        <input type="hidden" id="productId" value="">

                        <div class="row g-3">
                            <!-- Product Name -->
                            <div class="col-md-6">
                                <label class="form-label">Product Name</label>
                                <input type="text" class="form-control" id="product_name">
                            </div>

                            <!-- Slug -->
                            <div class="col-md-6">
                                <label class="form-label">Product Slug</label>
                                <input type="text" class="form-control" id="product_slug">
                            </div>

                            <!-- Quantity -->
                            <div class="col-md-6">
                                <label class="form-label">Quantity</label>
                                <input type="number" class="form-control" id="qty">
                            </div>

                            <!-- Status -->
                            <div class="col-md-6">
                                <label class="form-label">Status</label>
                                <input type="text" class="form-control" id="status">
                            </div>

                            <!-- Product Price -->
                            <div class="col-md-12">
                                <label class="form-label">Product Price (₱)</label>
                                <input type="number" class="form-control" id="product_price">
                            </div>

                            <!-- Description -->
                            <div class="col-md-12">
                                <label class="form-label">Product Description</label>
                                <textarea class="form-control" id="product_description" rows="3"></textarea>
                            </div>

                            <!-- Product Image -->
                            <div class="col-md-12">
                                <label class="form-label">Product Image</label><br>
                                <img id="product_img" class="img-thumbnail" style="max-height: 200px; display: none;"
                                    alt="Product Image" />
                                <p id="noProductImageText" class="text-muted">No image available</p>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="button" class="btn btn-success" id="approveBtn">Update</button>
                        </div>
                    </form>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>


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

            $('#approveBtn').on('click', function() {

                let productId = $('#productId').val();

                let data = {
                    product_name: $('#product_name').val(),
                    product_slug: $('#product_slug').val(),
                    qty: $('#qty').val(),
                    status: $('#status').val(),
                    product_price: $('#product_price').val(),
                    product_description: $('#product_description').val(),
                    _token: '{{ csrf_token() }}'
                };

                if (!data.product_name.trim()) {
                    alert("Product name is required");
                    return;
                }

                $.ajax({
                    url: '/admin/products/' + productId, // Laravel API route
                    method: 'PUT', // or 'PATCH'
                    data: data,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // for Laravel
                    },
                    success: function(response) {
                        alert('Product updated successfully!');
                        location.reload();
                        $('#viewOrderForm')[0].reset();
                        $('#viewOrderLabel').text('Product Details');
                        $('#viewOrderForm').closest('.modal').modal('hide');

                        // Optionally refresh table/list
                        // loadProducts();
                    },
                    error: function(xhr) {
                        let err = xhr.responseJSON?.message || "Error updating product.";
                        alert(err);
                    }
                });
            });


            $(document).on('click', '.btn-view-product', function() {

                const modal = $('#viewOrderModal');

                // Get product data from button
                const id = $(this).data('id');
                const name = $(this).data('name');
                const slug = $(this).data('slug');
                const description = $(this).data('description');
                const qty = $(this).data('qty');
                const status = $(this).data('status');
                const price = $(this).data('product_price');
                const img = $(this).data('img');


                // Set modal fields

                modal.find('#productId').val(id);
                modal.find('#product_name').val(name);
                modal.find('#product_slug').val(slug);
                modal.find('#qty').val(qty);
                modal.find('#status').val(status == 1 ? 'Active' : 'Inactive');
                modal.find('#product_price').val(price);
                modal.find('#product_description').val(description);

                // Show image or placeholder
                if (img) {
                    modal.find('#product_img').attr('src', img).show();
                    modal.find('#noProductImageText').hide();
                } else {
                    modal.find('#product_img').hide();
                    modal.find('#noProductImageText').show();
                }

                // Show the modal (if not using data-bs-toggle)
                bootstrap.Modal.getOrCreateInstance(modal[0]).show();
            });

            // delete modal

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
                const productId = $('#deleteProductId').val();

                $.ajax({
                    url: `/admin/products/${productId}`,
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

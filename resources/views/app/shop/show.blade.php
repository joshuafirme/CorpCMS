@extends('app._partials.app')

@section('content')
    <style>
        .preview-thumb {
            transition: transform 0.3s ease;
        }

        .preview-thumb:hover {
            transform: scale(1.1);
        }

        #quantity {
            font-size: 1.1rem;
            height: 45px;
            padding: 0;
            box-shadow: none;
        }
        #increase-btn, #decrease-btn {
            font-size: 1.25rem;
            padding: 0.375rem 0.75rem;
        }
    </style>
    <section class="bg-white py-5">
        <div class="container py-5">
            <h2 class="text-center mb-5 text-primary">Shop</h2>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="row g-0">
                            <!-- Left: Image Preview -->
                            <div class="col-md-6">
                                <div class="p-3">
                                    <div class="border rounded bg-light d-flex align-items-center justify-content-center" style="height: 400px;">
                                        <img id="mainImage" src="{{ $product->product_img ?? 'https://placehold.co/400x500?text=Coming+Soon' }}" class="img-fluid rounded object-fit-cover h-100" alt="Unseen Wins Book">
                                    </div>
                                    <div class="d-flex gap-2 justify-content-center mt-3">
                                        <img src="{{ $product->product_img ?? 'https://placehold.co/400x500?text=Coming+Soon '}}" class="img-thumbnail preview-thumb" onclick="changePreview(this)" style="width: 70px; height: 100px; object-fit: cover; cursor: pointer; border: 2px solid #272829;">
                                        <img src="{{ $product->product_img_preview_1 ?? 'https://placehold.co/400x500?text=Back+Cover' }}" class="img-thumbnail preview-thumb" onclick="changePreview(this)" style="width: 70px; height: 100px; object-fit: cover; cursor: pointer;">
                                        <img src="{{ $product->product_img_previe_2 ?? 'https://placehold.co/400x500?text=Sample+Page' }}" class="img-thumbnail preview-thumb" onclick="changePreview(this)" style="width: 70px; height: 100px; object-fit: cover; cursor: pointer;">
                                    </div>
                                </div>
                            </div>

                            <!-- Right: Product Details -->
                            <div class="col-md-6 d-flex flex-column justify-content-between p-4">
                                <div class="mt-3">
                                    <label for="quantity" class="form-label fw-semibold">Quantity</label>
                                    <div class="input-group" style="max-width: 160px;">
                                        <button id="decrease-btn" class="btn btn-outline-secondary px-3" type="button">−</button>
                                        <input type="text" class="form-control text-center fw-semibold" id="quantity" value="1" readonly style="min-width: 50px; font-size: 1.1rem;">
                                        <button id="increase-btn" class="btn btn-outline-secondary px-3" type="button">+</button>
                                    </div>
                                </div>
                                <div class="mt-3">
                                    <label for="quantity" class="form-label">Stock: {{ $product->qty ?? 1 }} pcs</label>
                                </div>

                                <div>
                                    <h3 class="text-primary fw-bold mb-2">{{ $product->product_name }}</h3>
                                    <p class="text-muted mb-1">by Coach Xavier Nunag</p>
                                    <h5 class="text-secondary fw-semibold mt-3 mb-2"> {{ $product->product_price }}</h5>
                                    <p class="text-dark mb-2">{{ $product->product_description }}</p>
                                    <p class="text-danger small fw-medium mb-0">📍 Now available in select areas in the Philippines.<br>For nationwide/international orders, use the Amazon link below.</p>
                                </div>
                                <div class="mt-4 d-flex gap-3">
                                    <a href="{{  $product->product_amazon }}" target="_blank" class="btn btn-primary px-4">Buy on Amazon</a>
                                    <a href="#inquiry-form" class="btn btn-outline-secondary">Local Inquiry</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Order / Inquiry Form -->
            <div id="inquiry-form" class="mt-5 p-4 bg-light rounded">
                <h3 class="text-primary mb-4">Order / Inquiry</h3>
                <p class="mb-4 text-muted">We currently accept orders in select areas in the Philippines. Please fill out the form below to inquire about availability and delivery options.</p>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Your full name" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" class="form-control" id="email" placeholder="you@example.com" required>
                    </div>
                    <div class="mb-3">
                        <label for="book" class="form-label">Book Title</label>
                        <input type="text" class="form-control" id="book" value="Unseen Wins" required>
                    </div>
                    <div class="mb-3">
                        <label for="address" class="form-label">Address</label>
                        <textarea class="form-control" id="address" rows="2" placeholder="Delivery address street corner.."></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="message-note" class="form-label">Message / Note</label>
                        <textarea class="form-control" id="note" rows="4" placeholder="Add notes or message"></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="paymentProof" class="form-label">Upload Proof of Payment</label>
                        <input class="form-control" type="file" id="paymentProof" accept="image/*,application/pdf">
                        <small class="text-muted">Accepted formats: JPG, PNG, or PDF (max 5MB)</small>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit Order	</button>
                </form>
            </div>
        </div>
    </section>


    <section class="bg-white py-5">
        <div class="svg-border-waves text-primary">
            <!-- Wave SVG Border-->
            <svg class="wave" style="pointer-events: none" fill="currentColor" preserveAspectRatio="none"
                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1920 75">
                <defs>
                    <style>
                        .a {
                            fill: none;
                        }

                        .b {
                            clip-path: url(#a);
                        }

                        .d {
                            opacity: 0.5;
                            isolation: isolate;
                        }
                    </style>
                </defs>
                <clipPath id="a">
                    <rect class="a" width="1920" height="75"></rect>
                </clipPath>
                <g class="b">
                    <path class="c"
                        d="M1963,327H-105V65A2647.49,2647.49,0,0,1,431,19c217.7,3.5,239.6,30.8,470,36,297.3,6.7,367.5-36.2,642-28a2511.41,2511.41,0,0,1,420,48">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M-127,404H1963V44c-140.1-28-343.3-46.7-566,22-75.5,23.3-118.5,45.9-162,64-48.6,20.2-404.7,128-784,0C355.2,97.7,341.6,78.3,235,50,86.6,10.6-41.8,6.9-127,10">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1979,462-155,446V106C251.8,20.2,576.6,15.9,805,30c167.4,10.3,322.3,32.9,680,56,207,13.4,378,20.3,494,24">
                    </path>
                </g>
                <g class="b">
                    <path class="d"
                        d="M1998,484H-243V100c445.8,26.8,794.2-4.1,1035-39,141-20.4,231.1-40.1,378-45,349.6-11.6,636.7,73.8,828,150">
                    </path>
                </g>
            </svg>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function () {
          $('#increase-btn').click(function () {
            let qty = parseInt($('#quantity').val());
            $('#quantity').val(qty + 1);
            });

            $('#decrease-btn').click(function () {
                let qty = parseInt($('#quantity').val());
                if (qty > 1) {
                    $('#quantity').val(qty - 1);
                }
            });

            // Handle form submit
            $('form').on('submit', function (e) {
                e.preventDefault(); // Prevent default form submission

                // Get other fields
                const name = $('#name').val();
                const email = $('#email').val();
                const book = $('#book').val();
                const address = $('#address').val(); // assuming you renamed the textarea
                const note = $('#note').val();       // assuming you renamed the textarea
                const quantity = $('#quantity').val();

                // Manually get file
                const fileInput = $('#paymentProof')[0];
                const file = fileInput.files[0];

                if (file) {
                    console.log('Payment Proof File:', file.name);
                } else {
                    console.log('No payment proof file selected.');
                }

                // Simulate storing path or pass file name to back-end
                const fileName = file ? file.name : null;

                 // Prepare FormData just for actual upload (required for files)
                const formData = new FormData();
                formData.append('name', name);
                formData.append('email', email);
                formData.append('book', book);
                formData.append('address', address);
                formData.append('note', note);
                formData.append('quantity', quantity);
                if (file) {
                    formData.append('payment_proof', file);
                }

                $.ajax({
                    url: '../orders/store-order',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (res) {
                        alert('Order submitted successfully!');
                        console.log(res);
                    },
                    error: function (err) {
                        alert('Failed to submit order.');
                        console.error(err);
                    }
                });
            });
        });
        function changePreview(el) {
            const mainImage = document.getElementById('productPreview');
            const thumbs = document.querySelectorAll('.preview-thumb');

            // Update main image
            mainImage.src = el.src;

            // Toggle active style
            thumbs.forEach(img => img.style.border = '2px solid transparent');
            el.style.border = '2px solid #272829';
        }
    </script>
@endsection

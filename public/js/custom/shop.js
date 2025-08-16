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
                    url: 'orders/store-order',
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
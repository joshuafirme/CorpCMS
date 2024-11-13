<script>
    $(function() {

        "use strict";

        function clearInputs() {
            let modal = $('#updateModal');
            let inputs = modal.find('input,select');
            $('.bundle-choices').addClass('d-none');
            $.each(inputs, function(i, v) {
                if (i > 1) {
                    if ($(v).attr('type') == 'checkbox') {
                        modal.find('input[type="checkbox"]').prop('checked', false);
                        return;
                    }
                    $(v).val('');
                }
            });
        }

        $(document).on('click', '.btn-edit', function() {

            let v = $(this);
            clearInputs();
            $('#tbl-tracking tbody').empty();
            let data = v.attr('data-item');
            let mdl = $('#updateModal');
            mdl.find('.password-container').remove();
            $('#change-password').remove();
            if (data) {

                data = JSON.parse(data)

                mdl.find('.modal-title').text('Update user');
                mdl.find('form').attr('action', "/admin/users/update/" + data.id);

                for (var key of Object.keys(data)) {
                    mdl.find(`[name='${key}']`).val(data[key])
                }
                
                let change_pass_html = '<div class="d-block" id="change-password">';
                change_pass_html +=
                    '<a class="btn btn-sm btn-primary btn-change-pass">Change Password</a>';
                change_pass_html += '</div>';

                mdl.find('.modal-body').append(change_pass_html);

                return;
            } else {
                mdl.find('.modal-title').text('Add user');
                
                let password_container = '<div class="col-md-6 mt-3 password-container">';
                password_container += '<label for="validationCustom02" class="form-label">Password</label>';
                password_container += '<input type="password" class="form-control" name="password" required  autocomplete="new-password">';
                password_container += '</div>';

                mdl.find('.modal-body').append(password_container);

                mdl.find('form').attr('action', `{{ route('users.store') }}`)
                    mdl.find(`[name='status']`).val(1)
            }

            return false;
        });

        

        $(document).on('click', '#change-password .btn-change-pass', function() {
            $(this).remove();
            let change_pass_html = '<div col-md-6><label class="mt-2">New Password</label>';
            change_pass_html += '<div class="d-flex">';
            change_pass_html += '<input type="password" class="form-control" name="password" autocomplete="new-password" required>';
            change_pass_html += '<a class="btn btn-sm btn-danger" id="btn-cancel">Cancel</a>';
            change_pass_html += '</div></div>';

            $('#change-password').append(change_pass_html);
        });

        $(document).on('click', '#btn-cancel', function(event) {
            $(this).parent().parent().remove();
            $(this).remove();
            let change_pass_html = '<div class="d-flex col-md-6">';
            change_pass_html += '<a class="btn btn-sm btn-primary btn-change-pass">Change password</a>';
            change_pass_html += '</div>';

            $('#change-password').append(change_pass_html);
        });


        $(document).on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Please confirm',
                text: "You are sure do you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'DELETE',
                            url: "/admin/users/" + id,
                            data: {
                                _token: "{{ csrf_token() }}"
                            }
                        })

                        .done(function(data) {
                            $('#' + id).remove();
                            swal(data.message)
                        })
                        .fail(function() {
                            swal("Error occured. Please try again.", 'error');
                        });
                }
            })
        });
    });
</script>

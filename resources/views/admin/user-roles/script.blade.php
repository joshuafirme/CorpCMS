<script>
    $(function() {
        "use strict";

        $("#customCheck-all").on('click', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            $('div .ic_div-show').toggle();
        });

        $(document).on('click','.ic-parent-permission', function () {
            let parent_id = $(this).val();

            if ($(`#chkbx-all-${parent_id}`).is(':checked')) {
                $(`.parent-identy-${parent_id}`).each(function () {
                    $(this).prop('checked', true);
                });
            } else {
                $(`.parent-identy-${parent_id}`).each(function () {
                    $(this).prop('checked', false);
                });
            }
        });

        function clearInputs() {
            $('[name=name]').val('');
            $('input:checkbox:checked').prop('checked', false);
        }

        $('.open-modal').click(function(event) {

            let modal = $('#roleModal');
            modal.modal('show');

            let modal_type = $(this).attr('modal-type');

            clearInputs();

            if (modal_type == 'create') {
                $('[name=status]').val(1);
                modal.find('.modal-title').text('Create Role');
                modal.find('form').attr('action', "{{ route('user-roles.store') }}");

            } else {
                modal.find('.modal-title').text('Update Role');

                let data = JSON.parse($(this).attr('data-info'));

                modal.find('form').attr('action', "/admin/user-roles/update/" + data.id);
                modal.find('[name=name]').val(data.name);

                let permissions = data.permissions ? JSON.parse(data.permissions) : [];

                for (let i = 0; i < permissions.length; i++) {
                    $('#chkbx-' + permissions[i].replace(/\s/g, '')).prop('checked', true);
                }

                let parent_checkboxes = ['maintenance', 'administration','pages'];
                let checked_counter = 0;

                for (let el of parent_checkboxes) {
                    $(`.parent-identy-${el}`).each(function () {
                        if ($(this).is(':checked')) {
                            checked_counter++;
                        }
                        if ($(`.parent-identy-${el}`).length == checked_counter) {
                            $(`#chkbx-all-${el}`).prop('checked', true);
                            checked_counter = 0;
                        }
                    });
                }

            }


        });

        
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).attr('data-id');
            Swal.fire({
                title: 'Please confirm',
                text: "You are going to delete this data. All contents related with this data will be lost. Do you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                            type: 'DELETE',
                            url: "/admin/user-roles/" + id,
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

<script>
    $(function() {

        "use strict";

        let editorInstance;

        if ($('#editor').length > 0) {
            ClassicEditor
                .create(document.querySelector('#editor'), {
                    ckfinder: {
                        uploadUrl: '{{ env('APP_URL') }}ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'
                    }
                })
                .then(editor => {
                    // Store the editor instance
                    editorInstance = editor;
                })
                .catch(error => {
                    console.error(error);
                });
        }

        $(document).on('keyup', 'input[name="name"]', function() {
            let name = $(this).val();
            $('input[name="slug"]').val(slugify(name))
        });

        $(document).on('click', '.btn-edit', function() {

            let v = $(this);
            clearInputs();
            let data = v.attr('data-item');
            let mdl = $('#updateModal');

            if (data) {

                data = JSON.parse(data)
                console.log(data)

                mdl.find('.modal-title').text('Update Category');
                mdl.find('form').attr('action', "/admin/gallery/update/" + data.id);

                for (var key of Object.keys(data)) {
                    if (key == 'image' && data[key]) {
                        mdl.find('#' + key).attr('src', '/' + data[key]);
                        continue;
                    }
                    if (key == 'content' && data[key]) {
                        editorInstance.setData(data[key]);
                        continue;
                    }
                    mdl.find(`[name='${key}']`).val(data[key])
                }
                return;
            } else {
                mdl.find('.modal-title').text('Add Category');

                mdl.find('form').attr('action', `{{ route('gallery.store') }}`)
                mdl.find(`[name='status']`).val(1)
            }

            return false;
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
                            url: "/admin/gallery/" + id,
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

    });
</script>

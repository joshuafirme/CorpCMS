$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

function swal(html = '', type = 'success') {
    Swal.fire({
        icon: type,
        title: '',
        html: html,
    })
}

function clearInputs() {
    let modal = $('#updateModal, #createPost');
    let inputs = modal.find('input:not(.except),select,textarea,img:not(.img-fluid)');
    $('.bundle-choices').addClass('d-none');
    $.each(inputs, function(i, v) {
        if ($(v).attr('name') != '_token') {
            if ($(v).attr('type') == 'checkbox') {
                modal.find('input[type="checkbox"]').prop('checked', false);
                return;
            }
            $(v).val('');
            $(v).attr('src', '');
        }
    });
}

function formatDate(date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
}
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
    $.each(inputs, function (i, v) {
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

function slugify(str) {
    str = str.replace(/^\s+|\s+$/g, ''); // trim leading/trailing white space
    str = str.toLowerCase(); // convert string to lowercase
    str = str.replace(/[^a-z0-9 -]/g, '') // remove any non-alphanumeric characters
        .replace(/\s+/g, '-') // replace spaces with hyphens
        .replace(/-+/g, '-'); // remove consecutive hyphens
    return str;
}

function formatDate(dateString) {
    const months = [
        "January", "February", "March", "April", "May", "June",
        "July", "August", "September", "October", "November", "December"
    ];

    const date = new Date(dateString);

    const month = months[date.getMonth()];
    const day = date.getDate();
    const year = date.getFullYear();

    let hours = date.getHours();
    const minutes = date.getMinutes().toString().padStart(2, '0');

    const ampm = hours >= 12 ? 'PM' : 'AM';

    hours = hours % 12;
    hours = hours ? hours : 12;
    const formattedDate = `${month} ${day}, ${year} ${hours}:${minutes} ${ampm}`;

    return formattedDate;
}


async function openDatabase() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open('firebase-messaging-db', 1);
        request.onerror = event => reject(event);
        request.onsuccess = event => resolve(event.target.result);
        request.onupgradeneeded = event => {
            const db = event.target.result;
            if (!db.objectStoreNames.contains('firebase-data')) {
                db.createObjectStore('firebase-data');
            }
        };
    });
}
async function getData(key) {
    return this.openDatabase().then(db => {
        return new Promise((resolve, reject) => {
            const transaction = db.transaction(['firebase-data'], 'readonly');
            const objectStore = transaction.objectStore('firebase-data');
            const request = objectStore.get(key);
            request.onsuccess = event => resolve(event.target.result);
            request.onerror = event => reject(event);
        });
    });
}
async function deleteData(key) {
    return this.openDatabase().then(db => {
        const transaction = db.transaction(['firebase-data'], 'readwrite');
        const objectStore = transaction.objectStore('firebase-data');
        const request = objectStore.delete(key);
        return new Promise((resolve, reject) => {
            request.onsuccess = () => resolve(`Data with key ${key} has been deleted`);
            request.onerror = event => reject(event);
        });
    });
}
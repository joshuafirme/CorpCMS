const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            user: {},
            map: null,
            service_request: null,
            statuses: ['pending', 'accepted', 'ongoing', 'completed', 'cancelled'],
            routeCoordinates: [],
            index: 0,
            directions: '',
            directionsService: null,
            directionsRenderer: null,

            category_id: null,
            subcategory_id: null,
            description: null,

            success_msg: null,
            err_msg: null,
        };
    },
    methods: {
        async getServiceDetails(event) {
            let _this = this;

            let default_html = $(event).html();

            $(event).html(`<i class="fas fa-spinner fa-pulse"></i>`);

            let request_id = $(event).attr('data-id')

            console.log('request_id', request_id)

            const res = await fetch(`${_this.app_url}api/service-request/details/${request_id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();
            console.log('service_reques data', data)

            this.service_request = data;
            this.category_id = this.service_request.category_id;
            this.subcategory_id = this.service_request.subcategory_id;
            this.provider_id = this.service_request.provider_id;
            this.description = this.service_request.description;
            this.status = this.service_request.status;


            $('#updateModal').modal('show');

            $(event).html(default_html);
        },
        resetModel() {
            this.category_id = '';
            this.subcategory_id = '';
            this.provider_id = '';
            this.description = '';
            this.status = '';
        },
        async initializeMap() {
            let _this = this;

            let latlng = {};
            console.log('_this.service_request.customer', _this.service_request.customer)
            if (_this.service_request.customer.lat && _this.service_request.customer.lng) {
                latlng = {
                    lat: parseFloat(_this.service_request.customer.lat),
                    lng: parseFloat(_this.service_request.customer.lng)
                }
            } else {
                latlng = {
                    lat: 14.5995,
                    lng: 120.9842
                }
            }

            _this.map = new google.maps.Map(document.getElementById('map'), {
                center: latlng,
                zoom: 12
            });

            if (_this.service_request.service_provider && _this.service_request.service_provider.user) {
                // Initialize the directions service and renderer
                _this.directionsService = new google.maps.DirectionsService({
                    map: _this.map,
                    suppressMarkers: true // Suppress the default A and B markers
                });
                _this.directionsRenderer = new google.maps.DirectionsRenderer({
                    map: _this.map,
                    suppressMarkers: true // Suppress the default A and B markers
                });

                // Set the directions renderer to the map
                _this.directionsRenderer.setMap(_this.map);

                var start = {
                    lat: parseFloat(_this.service_request.service_provider.user.lat),
                    lng: parseFloat(_this.service_request.service_provider.user.lng)
                };
                var end = {
                    lat: parseFloat(_this.service_request.customer.lat),
                    lng: parseFloat(_this.service_request.customer.lng)
                };

                let start_marker = _this.setMarker(start, 'Start', "https://img.icons8.com/color/96/user-location.png");
                let end_marker = _this.setMarker(end, 'End', "https://img.icons8.com/glyph-neue/64/ff0000/marker--v1.png");

                _this.markerPopups(start_marker, '<div><h4>Service Provider</h4><p>This is the starting point.</p></div>');
                _this.markerPopups(end_marker, '<div><h3>Customer</h3><p>This is the end point.</p></div>');

                // Call the function to calculate and display the route
                _this.calculateAndDisplayRoute(start, end);

            } else if (_this.service_request.customer) {
                let customer_marker = _this.setMarker({
                    lat: parseFloat(_this.service_request.customer.lat),
                    lng: parseFloat(_this.service_request.customer.lng)
                }, 'Customer');
                _this.markerPopups(customer_marker, '<div><h4>Customer</h4></div>');
            }
        },
        calculateAndDisplayRoute(origin, destination) {
            this.directionsService.route({
                    origin: origin,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING,
                },
                (response, status) => {
                    if (status === "OK") {
                        this.directionsRenderer.setDirections(response);
                        this.routeCoordinates = response.routes[0].legs[0].steps.map(step => step.end_location);
                        this.index = 0; // Reset index for the new route
                        this.displayDirections(response.routes[0].legs[0].steps);
                    } else {
                        alert("Directions request failed due to " + status);
                    }
                }
            );
        },
        checkRoute() {
            if (this.routeCoordinates.length > 0) {
                let destination = {
                    lat: parseFloat(this.service_request.customer.lat),
                    lng: parseFloat(this.service_request.customer.lng)
                }
                const userLatLng = new google.maps.LatLng(destination);
                const nextStep = this.routeCoordinates[this.index];

                const distance = google.maps.geometry.spherical.computeDistanceBetween(userLatLng, nextStep);

                if (distance < 20) {
                    this.index++;
                    if (this.index < this.routeCoordinates.length) {
                        this.displayDirections(this.directionsRenderer.getDirections().routes[0].legs[0].steps);
                    } else {
                        alert("You have arrived at your destination!");
                    }
                }
            }
        },
        displayDirections(steps) {
            this.directions = ''; // Clear previous directions
            steps.forEach(step => {
                this.directions += step.instructions + '<br>';
            });
        },
        markerPopups(marker, content) {
            let _this = this;
            var info_window = new google.maps.InfoWindow({
                content: content,
            });

            // Add event listeners to open the popup on marker hover (mouseover)
            marker.addListener('mouseover', function () {
                info_window.open(_this.map, marker);
            });
            marker.addListener('mouseout', function () {
                info_window.close();
            });
        },
        setMarker(position, title, icon = null) {
            let _this = this;
            return new google.maps.Marker({
                position: position,
                map: _this.map,
                url: {
                    url: icon, // URL of the custom start icon
                    scaledSize: new google.maps.Size(30, 30), // Resize the icon if necessary
                },
                title: title,
            });
        },
        ucFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        formatDate(dateString) {
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
        },
        getStatusColor($status) {
            switch ($status) {
                case 'pending':
                    return 'primary';
                case 'accepted':
                    return 'warning';
                case 'completed':
                    return 'success';
                case 'cancelled':
                    return 'danger';
                default:
                    break;
            }
        },
        showToast(msg, type) {
            $.toast({
                text: msg,
                showHideTransition: 'slide',
                icon: type,
                hideAfter: 5000,
                position: 'bottom-right',
            })
        }
    },
    async mounted() {
        let _this = this;
        const modal = document.getElementById('updateModal');
        modal.addEventListener('shown.bs.modal', this.initializeMap);

        $(document).on('click', '.btn-add', function (e) {
            _this.resetModel()
            mdl.find('form').attr('action', `/admin/service-requests/store`)
        });

        $(document).on('click', '.btn-edit', function (e) {
            _this.getServiceDetails(this)
            let id = $(this).attr('data-id');
            let mdl = $('#updateModal');
            mdl.find('form').attr('action', `/admin/service-requests/update/${id}`)
        })

        $(document).on('click', '.btn-delete', function (e) {

            let id = $(this).attr('data-id');

            Swal.fire({
                title: 'Please confirm',
                text: "You are sure do you want to delete it?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes'
            }).then(async (result) => {
                if (result.isConfirmed) {
                    await axios({
                        method: "delete",
                        url: `/admin/service-requests/${id}`,
                        headers: {
                            Authorization: `Bearer ${_this.user_token}`
                        }
                    }).then(
                        async (response) => {

                                console.log(response)
                                if (response.data.success) {
                                    _this.success_msg = response.data.message;
                                    _this.showToast(response.data.message, 'success')
                                    $('#' + id).remove();
                                } else {
                                    _this.showToast(response.data.message, 'danger')
                                }
                                event.target.innerHTML = "Save changes";
                            },
                            (error) => {
                                let err_res = error.response.data;
                                let errors = err_res.errors;
                                console.log(error)
                                _this.err_msg = err_res.message ? err_res.message : '';
                                _this.showToast(_this.err_msg, _this.err_msg)
                            }
                    );
                }
            })
        })
    },
});

app.mount("#app");

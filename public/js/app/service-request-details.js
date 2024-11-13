const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            service_request: null,
            service_provider: {},
            accepted: false,
            map: null,
            marker: null,
            userMarker: null,
            routeCoordinates: [],
            index: 0,
            directions: '',
            directionsService: null,
            directionsRenderer: null,
            currentLocation: null,
            success_msg: "",
            err_msg: "",
            loading: "Loading map...",
            map_style: {
                color: {
                    primary: '#044CAC',
                    highlight: '#ff7800',
                    active: '#ff7800'
                }
            },
            request_id: null,
        };
    },
    methods: {
        async initializeMap() {
            let _this = this;

            if (!_this.map) {

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

                    let start_marker = _this.setMarker(start, 'Start', _this.service_request.service_provider.user.profile_img);
                    let end_marker = _this.setMarker(end, 'End', _this.service_request.customer.profile_img);

                    // let car_marker = _this.setMarker(end, 'Car', "https://img.icons8.com/isometric/50/car.png");

                    _this.markerPopups(start_marker, '<div>Service Provider</div>');
                    _this.markerPopups(end_marker, '<div>Customer</div>');

                    // Call the function to calculate and display the route
                    _this.calculateAndDisplayRoute(start, end);

                } else if (_this.service_request.customer) {
                    let customer_marker = _this.setMarker({
                        lat: parseFloat(_this.service_request.customer.lat),
                        lng: parseFloat(_this.service_request.customer.lng)
                    }, 'Customer');
                    _this.markerPopups(customer_marker, '<div><h4>Customer</h4></div>');
                }
            }

        },
        success(position) {
            this.currentLocation = {
                lat: position.coords.latitude,
                lng: position.coords.longitude,
            };

            if (!this.userMarker) {
                this.userMarker = new google.maps.Marker({
                    position: this.currentLocation,
                    map: this.map,
                    icon: {
                        url: "https://img.icons8.com/isometric/50/car.png",
                        scaledSize: new google.maps.Size(30, 30),
                    },
                });

                const destination = {
                    lat: this.service_request.customer.lat,
                    lng: this.service_request.customer.lng
                }; // Example destination
                this.calculateAndDisplayRoute(destination);
            } else {
                this.userMarker.setPosition(this.currentLocation);
                this.map.setCenter(this.currentLocation);
                this.checkRoute();
            }
        },
        error() {
            alert("Unable to retrieve your location.");
        },
        calculateAndDisplayRoute(destination) {
            this.directionsService.route({
                    origin: this.currentLocation,
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
                const userLatLng = new google.maps.LatLng(this.currentLocation.lat, this.currentLocation.lng);
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

            const priceTag = document.createElement("div");

            priceTag.className = "price-tag";
            priceTag.textContent = "$2.5M";

            return new google.maps.Marker({
                position: position,
                map: _this.map,
                // icon: {
                //     url: icon,
                //     scaledSize: new google.maps.Size(30, 30),
                //     shape: {
                //         coords: [17, 17, 18],
                //         type: 'circle'
                //     },
                // },
                title: title,
            });
        },
        calculateAndDisplayRoute(start, end) {
            let _this = this;
            _this.directionsService.route({
                    origin: start,
                    destination: end,
                    travelMode: google.maps.TravelMode.DRIVING, // You can change to WALKING, BICYCLING, etc.
                },
                function (response, status) {
                    if (status === google.maps.DirectionsStatus.OK) {
                        _this.directionsRenderer.setDirections(response); // Render the directions on the map
                    } else {
                        window.alert("Directions request failed due to " + status);
                    }
                }
            );
        },

        getCurrentLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(
                    (position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;

                        this.currentLocation = {
                            lat,
                            lng
                        };
                        this.setMarker(lat, lng);
                    },
                    (error) => {
                        console.error('Error retrieving location', error);
                    }
                );
            } else {
                alert('Geolocation is not supported by your browser.');
            }
        },
        async getServiceDetails() {
            let _this = this;
            let request_id = $('#request_id').val();

            const res = await fetch(`${_this.app_url}api/service-request/details/${request_id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();
            console.log('service_reques data', data)
            _this.service_request = data;

        },
        async getAccountInfo() {
            let _this = this;

            const res = await fetch(`${_this.app_url}api/account/${_this.user_id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();
            console.log('data', data)
            _this.service_provider = data ? data : {};

        },
        async acceptService(event, request_id) {
            let _this = this;
            event.preventDefault();
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Accepting request... &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;
            event.target.disabled = true;

            let url = `${this.app_url}api/service-request/accept/${request_id}`;

            await axios({
                method: "post",
                url: url,
                headers: {
                    Authorization: `Bearer ${_this.user_token}`,
                }
            }).then(
                async (response) => {

                        console.log(response)
                        if (response.data.success) {
                            _this.success_msg = response.data.message;

                            _this.service_request = response.data.service_request;

                            _this.accepted = true;
                            event.target.innerHTML = "Accepted";
                            event.target.disabled = true;
                            return;
                        } else {
                            _this.err_msg = response.data.message;
                        }
                        event.target.innerHTML = "Accept";
                        event.target.disabled = false;
                    },
                    (error) => {
                        let err_res = error.response.data;
                        let errors = err_res.errors;
                        event.target.disabled = false;
                        console.log(error)
                        _this.err_msg = err_res.message ? err_res.message : '';
                        if (errors) {
                            for (var key of Object.keys(errors)) {
                                for (let index = 0; index < errors[key].length; index++) {
                                    console.log(errors[key][index])
                                    _this.err_msg += errors[key][index] + '<br>'
                                }
                            }
                        }
                        event.target.innerHTML = btn_old_html;
                        _this.showToast();
                    }
            );
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
        async showToast() {
            var toast = new bootstrap.Toast(document.getElementById('liveToast'))
            toast.show()
        },

    },
    async mounted() {
        await this.getServiceDetails()
        await this.getAccountInfo()
        await this.initializeMap()
    },
});

app.mount("#app");

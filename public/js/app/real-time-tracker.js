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
        // Initialize the map inside the modal
        async initializeMap() {
            // Initialize the map
            let _this = this;

            if (!_this.map) {

                
                let lat_lng = {
                    lat: 14.5995,
                    lng: 120.9842
                };
                
                _this.map = new google.maps.Map(document.getElementById('map'), {
                    center: lat_lng,
                    zoom: 12,
                });

                // L.Routing.control({
                //     waypoints: [
                //         L.latLng(_this.service_request.customer.lat, _this.service_request.customer.lng), // Starting point (Manila)
                //         L.latLng(_this.service_provider.lat, _this.service_provider.lng) // Destination (Quezon City)
                //     ],
                //     lineOptions: {
                //         styles: [{
                //                 color: '#FFFFFF',
                //                 opacity: 1,
                //                 weight: 8
                //             },
                //             {
                //                 color: '#00AC69',
                //                 opacity: 0.8,
                //                 weight: 3
                //             }
                //         ] // Custom color and weight
                //     },
                //     routeWhileDragging: true // Enable real-time routing
                // }).addTo(_this.map);
            }

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

        // Pin location manually by clicking the map
        async pinLocationManually(event) {
            console.log(event)
            const {
                lat,
                lng
            } = event.latlng;
            this.setMarker(lat, lng);
            this.currentLocation = {
                lat,
                lng
            };

        },


        // Place or move a marker on the map
        async setMarker(lat, lng) {
            if (this.marker) {
                this.marker.remove();
            }

            var customIcon = L.icon({
                iconUrl: 'https://img.icons8.com/ios-filled/100/044cac/marker.png', // Replace with your image URL
                iconSize: [50, 50], // Set the size of the icon (width, height)
                iconAnchor: [19, 38], // Anchor point of the icon (usually half of icon width for centering)
                popupAnchor: [0, -38], // Position of the popup relative to the icon
            });

            // Add marker with drag option
            this.marker = L.marker([lat, lng], {
                    //     draggable: true
                    icon: customIcon
                })
                .addTo(this.map)
                .bindPopup('Pinned location')
                .openPopup();
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
            console.log('data', data)
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
                            _this.accepted = true;
                            event.target.innerHTML = "Accepted";
                            event.target.disabled = true;
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
        async showToast() {
            var toast = new bootstrap.Toast(document.getElementById('liveToast'))
            toast.show()
        }, // initFirebaseMessagingRegistration()

    },
    async mounted() {
        // await this.getServiceDetails()
        // await this.getAccountInfo()
        await this.initializeMap()
    },
});

app.mount("#app");

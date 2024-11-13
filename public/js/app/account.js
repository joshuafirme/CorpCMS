const app = Vue.createApp({
    data() {
        console.log(window.Laravel)
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            user: {},
            map: null,
            marker: null,
            currentLocation: null,
            formatted_address: null,
            lng: null,
            lat: null,
            area_code: null,
            area_name: null,
            profile_img: null,
            selected_barangay: null,
            success_msg: null,
            err_msg: null,
            loading: "Loading map...",
            map_style: {
                color: {
                    primary: '#044CAC',
                    highlight: '#ff7800',
                    active: '#ff7800'
                }
            }
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

                if (_this.user.lat && _this.user.lng) {
                    lat_lng = {
                        lat: parseFloat(_this.user.lat),
                        lng: parseFloat(_this.user.lng)
                    };
                }

                _this.map = new google.maps.Map(document.getElementById('map'), {
                    center: lat_lng,
                    zoom: 14,
                });

                setMarker(_this.map, lat_lng);
            }

            _this.map.addListener("click", async (event) => {
                _this.currentLocation = {
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                };

                setMarker(_this.map, event.latLng);

                await _this.getFormattedAddress();
                await _this.getAreaCodeFromLatLng(event.latLng.lat(), event.latLng.lng());
            });

            function setMarker(map, position, title = "You", icon = null) {

                let __this = this;

                if (!icon) {
                    icon = "https://img.icons8.com/ios-filled/100/044cac/marker.png";
                }

                if (__this.marker) {
                    __this.marker.setMap(null);
                    __this.marker = null;
                }

                __this.marker = new google.maps.Marker({
                    position: position,
                    map: map,
                    icon: {
                        url: icon, // URL of the custom start icon
                        scaledSize: new google.maps.Size(50, 50), // Resize the icon if necessary
                    },
                    title: title,
                    draggable: true,
                });

                google.maps.event.addListener(__this.marker, 'dragend', async function (event) {
                    // Get the new coordinates after dragging
                    const newLat = event.latLng.lat();
                    const newLng = event.latLng.lng();

                    // Output or store the new position
                    console.log("New position: ", {
                        lat: newLat,
                        lng: newLng
                    });
                    __this.currentLocation = {
                        lat: newLat,
                        lng: newLng
                    };

                    await _this.getFormattedAddress();
                    await _this.getAreaCodeFromLatLng(newLat, newLng);
                });

                console.log('marker set')
            }

            $(document).on('click', '#btnPinLocation', function () {
                getCurrentLocation()
            })

            function getCurrentLocation() {
                // Check if browser supports geolocation
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        async (position) => {
                                const latLng = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude,
                                };
                                console.log('latLng', latLng)
                                // Place the marker at the user's current location
                                _this.currentLocation = {
                                    lat: position.coords.latitude,
                                    lng: position.coords.longitude
                                };
                                setMarker(_this.map, latLng);
                                _this.map.setCenter(latLng);
                                await _this.getBarangayByCoordinates(position.coords.latitude, position.coords.longitude);

                            },
                            () => {
                                alert("Unable to retrieve your location.");
                            }, {
                                enableHighAccuracy: true
                            } // High accuracy option
                    );
                } else {
                    alert("Geolocation is not supported by this browser.");
                }
            }

        },

        async getAreaCodeFromLatLng(lat, lng) {
            let _this = this;

            await _this.getBarangayByCoordinates(lat, lng);
            if (_this.selected_barangay) {
                console.log("The area code for this location is: " + _this.selected_barangay.ID_3);
                _this.currentLocation = {
                    lat: lat,
                    lng: lng,
                    area_code: _this.selected_barangay.ID_3
                };
            } else {
                _this.currentLocation = {
                    lat: lat,
                    lng: lng,
                    area_code: null
                };
                console.log('No barangay found for this location.');
            }
        },

        async pinDone() {
            this.lat = this.currentLocation.lat
            this.lng = this.currentLocation.lng
            $('#mapModal').modal('hide')
        },

        async getFormattedAddress() {
            let _this = this;

            const url = `https://nominatim.openstreetmap.org/reverse?lat=${_this.currentLocation.lat}&lon=${_this.currentLocation.lng}&format=json`;

            await fetch(url)
                .then(response => response.json())
                .then(data => {
                    console.log('data', data)
                    _this.formatted_address = data.display_name;
                })
                .catch(error => console.error("Error fetching Nominatim data: ", error));
        },
        // Pin the user's current geolocation
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

            await this.getBarangayByCoordinates(lat, lng);
        },
        async getBarangayByCoordinates(lat, lng) {

            await fetch(`${this.app_url}data/geofence/Barangays.simplified.json`)
                .then(response => response.json())
                .then(barangaysData => {
                    for (const features of barangaysData.features) {

                        if (this.isPointInBarangay(lat, lng, features)) {
                            //   this.selected_barangay = barangay;
                            console.log(features)
                            console.log('found...')
                            this.area_code = features.properties.ID_3;
                            this.area_name = features.properties.NAME_3;
                        }
                    }
                })

        },
        isPointInBarangay(lat, lng, feature) {
            let isInside = false;
            // Create a GeoJSON point feature from the lat/lng
            const point = turf.point([lng, lat]);

            if (feature.geometry && feature.geometry.coordinates.length > 0 && feature.geometry.coordinates[0].length > 3) {
                var poly = turf.polygon(feature.geometry.coordinates);
                // Use Turf.js to check if the point is inside the polygon
                isInside = turf.booleanPointInPolygon(point, poly);
            }

            return isInside;
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

            // Update marker position on drag
            // this.marker.on('dragend', (event) => {
            //     const position = event.target.getLatLng();
            //     this.currentLocation = {
            //         lat: position.lat,
            //         lng: position.lng
            //     };
            // });

            // // Center the map on the marker
            // this.map.setView([lat, lng], 13);
        },
        async onChangeProfileImage(event) {
            const file = event.target.files[0];
            let _this = this;
            let reader = new FileReader();
            reader.onload = function (event) {
                _this.profile_img = event.target.result;
            };
            reader.readAsDataURL(file);
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
            _this.user = data ? data : {};
            console.log(_this.user.lat)
            _this.currentLocation = {
                lat: _this.user.lat,
                lng: _this.user.lng
            }
            _this.lat = _this.user.lat;
            _this.lng = _this.user.lng;
            _this.formatted_address = _this.user.formatted_address;
            _this.area_code = _this.user.area_code;
            _this.area_name = _this.user.area_name;
            _this.profile_img = _this.user.profile_img;

        },
        async saveAccount(event) {
            let _this = this;
            event.preventDefault();
            let user_id = $(event.target).attr('data-user_id');
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Saving &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;

            const form = document.getElementById("accountForm");

            let formData = new FormData(form);

            let url = `${this.app_url}api/users/update/${user_id}`;

            await axios({
                method: "post",
                url: url,
                data: formData,
                headers: {
                    Authorization: `Bearer ${_this.user_token}`,
                    'Content-Type': 'multipart/form-data'
                }
            }).then(
                async (response) => {

                        console.log(response)
                        if (response.data.success) {
                            _this.success_msg = response.data.message;
                            //  _this.getAccountInfo()
                        } else {
                            _this.err_msg = response.data.message;
                        }
                        event.target.innerHTML = "Save changes";
                    },
                    (error) => {
                        let err_res = error.response.data;
                        let errors = err_res.errors;
                        console.log(error)
                        _this.err_msg = err_res.message ? err_res.message : '';
                        event.target.innerHTML = btn_old_html;
                    }
            );
        },
        async sendEmailVerification(event) {
            let _this = this;
            event.preventDefault();
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Sending email... &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;
            event.target.disabled = true;

            let url = `${this.app_url}api/account/send-email-verification`;

            await axios({
                method: "post",
                url: url,
                headers: {
                    Authorization: `Bearer ${_this.user_token}`
                }
            }).then(
                async (response) => {

                        console.log(response)
                        if (response.data.success) {
                            _this.success_msg = response.data.message;
                        } else {
                            _this.err_msg = response.data.message;
                        }
                        event.target.innerHTML = btn_old_html;
                    },
                    (error) => {
                        let err_res = error.response.data;
                        console.log(error)
                        _this.err_msg = err_res.message ? err_res.message : '';
                        event.target.innerHTML = btn_old_html;
                    }
            );
        },
        async showToast() {
            var toast = new bootstrap.Toast(document.getElementById('notifToast'))
            toast.show()
        },
    },
    async mounted() {
        const modal = document.getElementById('mapModal');
        modal.addEventListener('shown.bs.modal', this.initializeMap);
        await this.getAccountInfo();
    },
});

app.mount("#app");

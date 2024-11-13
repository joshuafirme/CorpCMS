const app = Vue.createApp({
    data() {
        console.log(window.Laravel)
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            calendar: null,
            user: {},
            map: null,
            marker: null,
            currentLocation: null,
            formatted_address: null,
            lng: null,
            lat: null,
            area_code: null,
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

                if (_this.user.lat && _this.user.lng) {
                    _this.map = L.map('map').setView([_this.user.lat, _this.user.lng], 12); // Coordinates of Manila
                    await _this.setMarker(_this.user.lat, _this.user.lng)
                } else {
                    _this.map = L.map('map').setView([14.5995, 120.9842], 12); // Coordinates of Manila
                }

                // Set the tile layer for the map
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                    minZoom: 7,
                    maxZoom: 16,
                }).addTo(_this.map);
            }

            _this.map.on('click', async function (e) {
                var latlng = e.latlng;
                let lat = latlng.lat;
                let lng = latlng.lng;

                if (latlng) {
                    // Add a marker to the clicked location
                    await _this.setMarker(lat, lng)

                    _this.currentLocation = {
                        lat,
                        lng
                    };

                    // Get the area_code of this location
                    await getAreaCodeFromLatLng(lat, lng);
                    await _this.getFormattedAddress();
                }
            });

            async function getAreaCodeFromLatLng(lat, lng) {
                var point = L.latLng(lat, lng);

                // _this.barangay_layer.eachLayer(function (layer) {
                //     // Check if the point is inside the layer's polygon
                //     if (layer._bounds && layer._bounds._northEast && layer.getBounds().contains(point)) {
                //         _this.area_code = layer.feature.properties.ID_3;
                //         console.log("The area code for this location is: " + _this.area_code);
                //         return; // Stop looping once the area_code is found
                //     }
                // });

                await _this.getBarangayByCoordinates(lat, lng);
                if (_this.selected_barangay) {
                    console.log("The area code for this location is: " + _this.selected_barangay.ID_3);
                    _this.currentLocation = {
                        lat,
                        lng,
                        area_code: _this.selected_barangay.ID_3
                    };
                } else {
                    _this.currentLocation = {
                        lat,
                        lng,
                        area_code: null
                    };
                    console.log('No barangay found for this location.');
                }
            }

            await fetch(`${this.app_url}data/geofence/Barangays.simplified.json`)
                .then(response => response.json())
                .then(barangaysData => {
                    // Add the GeoJSON layer to the map
                    _this.barangay_layer = L.geoJSON(barangaysData, {
                        style: style,
                        onEachFeature: onEachFeature,
                        renderer: L.canvas({
                            padding: 0.5
                        })
                    }, {
                        onEachFeature: function (feature, layer) {
                            _this.map.on('moveend', function () {
                                var bounds = _this.map.getBounds();
                                // Load GeoJSON data within the current bounds
                                loadBarangays(bounds);
                            });
                        }
                    }).addTo(_this.map);

                    function onFeatureClick(e) {
                        var layer = e.target;
                        // Example: Show an alert or popup with Barangay details
                        let properties = layer.feature.properties;

                        // Set the fill color of the clicked Barangay
                        layer.setStyle({
                            color: '#044CAC',
                            fillColor: _this.map_style.color.highlight, // Highlight color for the selected Barangay
                            fillOpacity: 0.5
                        });

                    }


                    // Attach hover events to each feature in the GeoJSON
                    function onEachFeature(feature, layer) {
                        layer.on({
                            click: onFeatureClick,
                            mouseout: resetHighlight,
                        });
                    }

                    function resetHighlight(e) {
                        _this.barangay_layer.resetStyle();
                    }

                    function style(feature) {

                        return {
                            color: '#044CAC', // Border color (blue)
                            weight: 1, // Border thickness
                            opacity: 0.2, // Border opacity (1 = fully opaque)
                            fillColor: '#ffffff', // Fill color (white)
                            fillOpacity: 0.2 // Fill opacity
                        };
                    }
                })
                .catch(error => {
                    console.error("Error loading GeoJSON data:", error);
                });
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
            _this.profile_img = _this.user.profile_img;

        },
        async saveAvailability(event) {
            let _this = this;
            event.preventDefault();
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Saving &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;

            const form = document.getElementById("availabilityForm");

            let formData = new FormData(form);

            let url = `${this.app_url}api/service-provider/update-availability`;

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
                            _this.getAccountInfo()
                        } else {
                            _this.err_msg = response.data.message;
                        }
                        event.target.innerHTML = btn_old_html;
                    },
                    (error) => {
                        let err_res = error.response.data;
                        let errors = err_res.errors;
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
    },
    async mounted() {
        await this.getAccountInfo();
    },
});

app.mount("#app");

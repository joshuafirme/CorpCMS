const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            form: document.getElementById("requestForm"),
            category_choices: null,
            user: {},
            service_request: null,
            modal_title: "Finding Service Provider",
            check_service_interval: null,
            map: null,
            marker: null,
            currentLocation: null,
            selected_subcategory: null,
            payment_details: null,
            formatted_address: "",
            lng: "",
            lat: "",
            area_code: "",
            area_name: "",
            success_msg: "",
            err_msg: "",
            loading: true,
            map_style: {
                color: {
                    primary: '#044CAC',
                    highlight: '#ff7800',
                    active: '#ff7800'
                }
            },
            subcategories: [],
            category_choices: null,
            subcategory_choices: null,
            schedule_service: false,
            submit_success: false,
        };
    },
    methods: {
        // Initialize the map inside the modal
        async initAutoCompletePlace() {
            let _this = this;
            const input = document.getElementById("pac-input");
            const searchBox = new google.maps.places.SearchBox(input);

            // Bias the SearchBox results towards current map's viewport.
            _this.map.addListener("bounds_changed", () => {
                searchBox.setBounds(_this.map.getBounds());
            });

            let markers = [];

            // Listen for the event fired when the user selects a prediction and retrieve
            // more details for that place.
            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                // Clear out the old markers.
                markers.forEach((marker) => {
                    marker.setMap(null);
                });
                markers = [];

                // For each place, get the icon, name and location.
                const bounds = new google.maps.LatLngBounds();

                places.forEach((place) => {
                    if (!place.geometry || !place.geometry.location) {
                        console.log("Returned place contains no geometry");
                        return;
                    }

                    const icon = {
                        url: place.icon,
                        size: new google.maps.Size(71, 71),
                        origin: new google.maps.Point(0, 0),
                        anchor: new google.maps.Point(17, 34),
                        scaledSize: new google.maps.Size(25, 25),
                    };

                    // Create a marker for each place.
                    markers.push(
                        new google.maps.Marker({
                            map,
                            icon,
                            title: place.name,
                            position: place.geometry.location,
                        }),
                    );
                    if (place.geometry.viewport) {
                        // Only geocodes have viewport.
                        bounds.union(place.geometry.viewport);
                    } else {
                        bounds.extend(place.geometry.location);
                    }
                });
                _this.map.fitBounds(bounds);
            });
        },
        async initializeMap() {
            // Initialize the map
            let _this = this;

            if (!_this.map) {

                let lat_lng = {
                    lat: 14.5995,
                    lng: 120.9842
                };

                let guest_form = JSON.parse(localStorage.getItem('guest_request_form'));

                if (_this.user.lat && _this.user.lng) {
                    lat_lng = {
                        lat: parseFloat(_this.user.lat),
                        lng: parseFloat(_this.user.lng)
                    };
                } else if (guest_form && guest_form.lat) {
                    lat_lng = {
                        lat: parseFloat(guest_form.lat),
                        lng: parseFloat(guest_form.lng)
                    };
                }

                _this.map = new google.maps.Map(document.getElementById('map'), {
                    center: lat_lng,
                    zoom: 14,
                });

                console.log('lat_lng',lat_lng)

                _this.initAutoCompletePlace()

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
                    _this.currentLocation = {
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
            console.log('url----', url)
            await fetch(url)
                .then(response => response.json())
                .then(data => {
                    _this.formatted_address = data.display_name;
                })
                .catch(error => console.error("Error fetching Nominatim data: ", error));
        },

        async getBarangayByCoordinates(lat, lng) {

            await fetch(`${this.app_url}data/geofence/Barangays.simplified.json`)
                .then(response => response.json())
                .then(barangaysData => {
                    for (const features of barangaysData.features) {

                        if (this.isPointInBarangay(lat, lng, features)) {
                            //   this.selected_barangay = barangay;
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
        async scheduleToggle(event) {
            this.schedule_service = this.schedule_service ? false : true;
        },
        async categoryChange(event) {
            let _this = this;
            let category_id = $(event.target).find(":selected").val();
            _this.populateSubcategory(category_id);
        },
        async populateSubcategory(category_id = null) {
            let _this = this;
            if (!category_id) {
                category_id = $('#category_id').val();
            }

            if (category_id) {
                const res = await fetch(`${_this.app_url}api/subcategories?category_id=${category_id}`, {
                    headers: new Headers({
                        Authorization: "Bearer " + _this.user_token,
                    }),
                });

                let data = await res.json();
                _this.subcategories = data;
                _this.subcategory_choices.clearStore();
                _this.subcategory_choices.setChoices(function () {
                    return _this.subcategories.map(function (release) {
                        return {
                            label: release.name,
                            value: release.id
                        };
                    });
                })
            }
        },
        async setSelectedChoices() {
            let _this = this;
            var url = new URL(window.location.href);
            var selected_subcategory_id = url.searchParams.get("subcategory_id");
            if (selected_subcategory_id) {
                selected_subcategory_id = parseInt(selected_subcategory_id)
            } else {
                let guest_form = localStorage.getItem('guest_request_form');
                if (guest_form) {
                    guest_form = JSON.parse(guest_form);
                    if (guest_form.category_id !== "") {
                        _this.category_choices.setChoiceByValue(guest_form.category_id)
                    }
                    if (guest_form.subcategory_id !== "") {
                        selected_subcategory_id = parseInt(guest_form.subcategory_id)
                    }
                }
            }

            await this.populateSubcategory()
            console.log('selected_subcategory_id', selected_subcategory_id)
            _this.subcategory_choices.setChoiceByValue(selected_subcategory_id);


        },
        async getAccountInfo() {
            let _this = this;

            let guest_form = localStorage.getItem('guest_request_form');

            if (_this.user_id) {
                const res = await fetch(`${_this.app_url}api/account/${_this.user_id}`, {
                    headers: new Headers({
                        Authorization: "Bearer " + _this.user_token,
                    }),
                });

                let data = await res.json();
                _this.user = data ? data : {};
                _this.currentLocation = {
                    lat: _this.user.lat,
                    lng: _this.user.lng
                }
                _this.lat = _this.user.lat;
                _this.lng = _this.user.lng;
                _this.formatted_address = _this.user.formatted_address;
                _this.area_code = _this.user.area_code;
                _this.area_name = _this.user.area_name;
            } else if (guest_form) {
                guest_form = JSON.parse(guest_form);
                _this.lat = guest_form.lat;
                _this.lng = guest_form.lng;
                _this.formatted_address = guest_form.formatted_address;
                _this.area_code = guest_form.area_code;
                _this.area_name = guest_form.area_name;
                _this.description = guest_form.description;
                _this.currentLocation = {
                    lat: guest_form.lat,
                    lng: guest_form.lng
                }
            }

        },
        async submitRequest(event) {
            let _this = this;
            event.preventDefault();

            if (!_this.user_id) {
                let formData = new FormData(document.getElementById("requestForm"));
                let formDataStringified = JSON.stringify(Object.fromEntries(formData));
                localStorage.setItem('guest_request_form', formDataStringified)
                $('#loginModal').modal('show')
                return;
            }

            _this.success_msg = '';
            _this.err_msg = '';

            let is_valid = await _this.validateForm()
            if (!is_valid) {
                return;
            }

            let btn_old_html = event.target.innerHTML;
            event.target.innerHTML = "Submitting service request...";

            event.target.disabled = true;

            const form = document.getElementById("requestForm");
            let formData = new FormData(form);

            let url = `${this.app_url}api/service-request`;

            await axios({
                method: "post",
                url: url,
                data: formData,
                headers: {
                    Authorization: `Bearer ${_this.user_token}`,
                }
            }).then(
                async (response) => {
                        console.log(response)

                        if (response.data.success) {
                            localStorage.setItem('request_id', response.data.service_request.id)
                            $('#loadingModal').modal('show')
                        } else {
                            _this.err_msg = response.data.message;
                            $.toast({
                                heading: 'Warning',
                                text: response.data.message,
                                showHideTransition: 'slide',
                                icon: 'warning',
                                stack: 1,
                                hideAfter: false,
                                position: 'bottom-right',
                            })
                        }
                        event.target.disabled = false;
                        event.target.innerHTML = btn_old_html;

                    },
                    (error) => {
                        console.log(error)
                        event.target.disabled = false;
                        event.target.innerHTML = btn_old_html;
                        $.toast({
                            heading: 'Error',
                            text: error,
                            showHideTransition: 'slide',
                            icon: 'error',
                            hideAfter: false,
                            position: 'bottom-right',
                        })
                    }
            );
        },
        async validateForm() {
            let form = $("#requestForm");
            let validation = form.validate({
                //  rules: rules,
                messages: {
                    confirm_password: {
                        equalTo: "Passwords do not match"
                    }
                }
            });

            form.valid()

            if (validation.errorList == 0) {
                return true;
            }
            return false;
        },
        async getServiceDetails(request_id = null) {
            let _this = this;
            if (request_id) {
                const res = await fetch(`${_this.app_url}api/service-request/details/${request_id}`, {
                    headers: new Headers({
                        Authorization: "Bearer " + _this.user_token,
                    }),
                });

                let data = await res.json();
                _this.service_request = data;

                $('#loadingModal').modal('show')
            }

        },
        async checkServiceRequestStatus() {
            let _this = this;
            _this.getData('latestNotification')
                .then(payload => {
                    console.log('Retrieved notification from IndexedDB:', payload);
                    if (payload && payload.data) {
                        if (payload.data.action == 'provider_accepted') {
                            if (_this.service_request) {
                                $('#loadingModal').modal('show')
                            }
                            clearInterval(_this.check_service_interval);

                            _this.getServiceDetails(payload.data.request_id)


                            let provider_name = _this.service_request.service_provider.business_name;
                            provider_name = !provider_name ? _this.service_request.service_provider.user.first_name : '';

                            _this.modal_title = `${provider_name} accepted your service request.`;

                        }
                    }
                })
                .catch(err => console.error('Failed to retrieve data', err));

        },
        async initializeChoices() {
            let _this = this;
            if (this.$refs.category_choices) {
                this.category_choices = new Choices(this.$refs.category_choices, {
                    allowHTML: true,
                    searchPlaceholderValue: 'Search a category',
                })
            }

            if (this.$refs.subcategory_choices) {
                this.subcategory_choices = new Choices(this.$refs.subcategory_choices, {
                    allowHTML: true,
                    searchPlaceholderValue: 'Search a category',
                })


                this.subcategory_choices.passedElement.element.addEventListener(
                    'change',
                    async function (event) {
                        let id = event.detail.value;
                        const res = await fetch(`${_this.app_url}api/subcategory/${id}`, {
                            headers: new Headers({
                                Authorization: "Bearer " + _this.user_token,
                            }),
                        });

                        let data = await res.json();
                        _this.selected_subcategory = data;
                        _this.computePaymentDetails(data.price)
                    }
                );
            }

        },
        computePaymentDetails(price) {
            let percentage = 10 / 100;
            let app_fee_amount = parseFloat(price) * percentage;
            let total = parseFloat(price) + parseFloat(app_fee_amount);
            console.log('total', total)
            this.payment_details = {
                app_fee_percentage: 10 + '%',
                app_fee_amount: this.formatNumber(app_fee_amount),
                price: this.formatNumber(price),
                total: this.formatNumber(total)
            }
        },
        formatNumber(v) {
            return (Math.round(v * 100) / 100).toFixed(2);
        },
        async openDatabase() {
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
        },
        async getData(key) {
            return this.openDatabase().then(db => {
                return new Promise((resolve, reject) => {
                    const transaction = db.transaction(['firebase-data'], 'readonly');
                    const objectStore = transaction.objectStore('firebase-data');
                    const request = objectStore.get(key);
                    request.onsuccess = event => resolve(event.target.result);
                    request.onerror = event => reject(event);
                });
            });
        },
        async deleteData(key) {
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
    },
    async mounted() {
        // const modal = document.getElementById('mapModal');
        // modal.addEventListener('shown.bs.modal', this.initializeMap);
        await this.getAccountInfo();
        await this.initializeMap()
        await this.initializeChoices();
        await this.setSelectedChoices();

        this.loading = false;

        this.check_service_interval = setInterval(this.checkServiceRequestStatus, 1000);

        await this.deleteData('latestNotification');
    },
});

app.mount("#app");

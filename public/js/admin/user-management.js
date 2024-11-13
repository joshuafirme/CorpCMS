const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            modal_title: "Add",
            user: null,

            id: null,
            first_name: null,
            last_name: null,
            middle_name: null,
            suffix: null,
            email: null,
            phone: null,
            formatted_address: null,
            wallet_balance: '0.00',
            transactions: null,
            verified: false,
            status: 1,
            service_provider: null,
            operational_area_codes: [],
            area_codes: [],
            service_areas: [],
            currentLocation: null,

            map: null,
            user: null,

            category_id: null,
            subcategory_id: null,

            success_msg: null,
            err_msg: null,
        };
    },
    methods: {
        async getDetails(event) {
            let _this = this;

            let default_html = $(event).html();

            $(event).html(`<i class="fas fa-spinner fa-pulse"></i>`);

            let request_id = $(event).attr('data-id')

            console.log('request_id', request_id)

            const res = await fetch(`${_this.app_url}api/user/${request_id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();


            this.user = data;

            this.id = data.id;
            this.first_name = data.first_name;
            this.last_name = data.last_name;
            this.middle_name = data.middle_name;
            this.suffix = data.suffix;
            this.email = data.email;
            this.phone = data.phone;
            this.formatted_address = data.formatted_address;
            this.wallet_balance = data.wallet_balance;
            this.verified = data.service_provider ? data.service_provider.verified : '';
            this.service_provider = data.service_provider ? data.service_provider : null;
            this.status = data.status;

            console.log('this.user', this.user)

            $('#postModal').modal('show');

            $(event).html(default_html);
        },
        resetModel() {
            this.id = '',
                this.first_name = '';
            this.last_name = '';
            this.middle_name = '';
            this.suffix = '';
            this.email = '';
            this.phone = '';
            this.formatted_address = '';
            this.wallet_balance = '0.00';
            this.verified = 0;
            this.status = 1;
            this.service_provider = null;
        },
        async getTransactions(event) {
            let _this = this;
            const res = await fetch(`${_this.app_url}api/user/wallet-transactions/${_this.id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();

            _this.transactions = data ? data : [];

            console.log('_this.transactions',_this.transactions)

        },
        async updateWallet(event) {
            let _this = this;
            event.preventDefault();
            let user_id = $(event.target).attr('data-user_id');
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Saving &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;

            const form = document.getElementById("walletForm");

            let formData = new FormData(form);

            if (!$('input[name="adjust_amount"').val()) {
                _this.showToast('Please enter amount', 'warning')
                event.target.innerHTML = btn_old_html;
                return
            }

            let url = `${this.app_url}api/user/update-wallet/${_this.id}`;

            await axios({
                method: "post",
                url: url,
                data: formData,
                headers: {
                    Authorization: `Bearer ${_this.user_token}`
                }
            }).then(
                async (response) => {
                        console.log(response)
                        if (response.data.success) {
                            _this.showToast(response.data.message, 'success')
                            $('#walletModal').modal('hide')
                            setTimeout(() => {
                                location.reload();
                            }, 3000);
                        } else {
                            _this.showToast(response.data.message, 'danger')
                        }
                        event.target.innerHTML = btn_old_html;
                    },
                    (error) => {
                        _this.showToast(error, 'danger')
                        event.target.innerHTML = btn_old_html;
                    }
            );
        },
        async validateForm(form) {
            let validation = $(form).validate();

            form = $(form).valid()

            if (validation.errorList == 0) {
                return true;
            }
            return false;
        },
        async initializeMap() {
            let _this = this;
            let show_marker = true;
            let latlng = {};

            if (_this.user && _this.user.service_provider && _this.user.service_provider.lat && _this.user.service_provider.lng) {
                latlng = {
                    lat: parseFloat(_this.user.service_provider.lat),
                    lng: parseFloat(_this.user.service_provider.lng)
                }

            } else if (_this.user && _this.user.lat) {
                latlng = {
                    lat: parseFloat(_this.user.lat),
                    lng: parseFloat(_this.user.lng)
                }
            } else {
                latlng = {
                    lat: 14.5995,
                    lng: 120.9842
                }
                show_marker = false;
            }

            _this.map = new google.maps.Map(document.getElementById('map'), {
                center: latlng,
                zoom: 14
            });

            setMarker(_this.map, latlng);

            _this.map.addListener("click", async (event) => {
                _this.currentLocation = {
                    lat: event.latLng.lat(),
                    lng: event.latLng.lng()
                };

                setMarker(_this.map, event.latLng);

                await _this.getFormattedAddress();
                await _this.getAreaCodeFromLatLng(event.latLng.lat(), event.latLng.lng());
            });

            // if (show_marker) {
            //     _this.markerPopups(setMarker(latlng, 'Provider'), '<div><p>Customer</p></div>');
            // }

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
            // Load the GeoJSON (barangay boundaries)
            await _this.map.data.loadGeoJson(`${this.app_url}api/operational-areas/brgy-geojson`, null, async function (features) {
                console.log('GeoJSON data loaded:', features.length + ' features added');
                // Perform any post-load actions here
                _this.loading = '';
                var infowindow = new google.maps.InfoWindow({
                    content: 'Your content here',
                    disableAutoPan: true, // Prevent auto-panning to the popup
                    pixelOffset: new google.maps.Size(0, -30) // Adjust popup position if needed
                });

                _this.service_areas = [];
                _this.area_codes = [];

                if (_this.user.service_provider && _this.user.service_provider.service_areas) {
                    _this.service_areas = _this.user.service_provider.service_areas;
                    _this.area_codes = _this.user.service_provider.service_areas.map(a => a.area_code);
                }

                if (_this.service_areas.length > 0) {
                    await _this.zoomToLocation(_this.service_areas[0].area_code)
                }

                _this.map.data.setStyle(function (feature) {
                    var areaCode = feature.getProperty('ID_3');
                    if (_this.idExists(_this.service_areas, areaCode)) {
                        // Return a style for active barangays
                        console.log('active', areaCode)
                        return {
                            fillColor: '#F77000',
                            fillOpacity: 0.7,
                            strokeColor: '#F77000', // Border color
                            strokeWeight: 0.4 // Border thickness
                        };
                    } else {
                        if (_this.operational_area_codes.includes(areaCode.toString())) {
                            return {
                                fillColor: '#F77000', // Set to any color (not visible due to fillOpacity being 0)
                                fillOpacity: 0.3,
                                strokeColor: '#A44A00', // Border color
                                strokeWeight: 0.4 // Border thickness
                            };
                        } else {
                            return {
                                fillColor: '#AAAAAA', // Set to any color (not visible due to fillOpacity being 0)
                                fillOpacity: 0.4,
                                strokeColor: '#AAAAAA', // Border color
                                strokeWeight: 0.4 // Border thickness
                            };
                        }
                    }
                });

                if (_this.user.service_provider) {
                    _this.map.data.addListener('click', function (event) {
                        console.log(event)
                        let layer_id = event.feature.getProperty('ID_3');
                        let province = event.feature.getProperty('NAME_1');
                        let municipality = event.feature.getProperty('NAME_2');
                        let area_name = event.feature.getProperty('NAME_3');

                        if (_this.operational_area_codes.includes(layer_id.toString())) {
                            console.log(_this.area_codes, layer_id)
                            if (!_this.area_codes.includes(layer_id)) {
                                console.log('clicked area code', layer_id)
                                _this.service_areas.push({
                                    province: province,
                                    municipality: municipality,
                                    area_name: area_name,
                                    area_code: layer_id
                                });

                                _this.map.data.overrideStyle(event.feature, {
                                    fillColor: '#F77000',
                                    fillOpacity: 0.7,
                                    strokeColor: '#A44A00', // Border color
                                    strokeWeight: 0.4 // Border thickness
                                });

                                _this.service_areas.push({
                                    area_name: area_name,
                                    area_code: layer_id
                                });

                                _this.area_codes = _this.service_areas.map(a => a.area_code);
                                _this.area_names = _this.service_areas.map(a => a.area_name);
                            } else {
                                // console.log('unselected area code', layer_id)
                                // _this.map.data.overrideStyle(event.feature, {
                                //     fillColor: '#F77000',
                                //     fillOpacity: 0.3,
                                //     strokeColor: '#A44A00',
                                //     strokeWeight: 0.4
                                // });
                                // console.log('old _this.area_codes', _this.area_codes)
                                // _this.area_codes = _this.removeItem(_this.area_codes, layer_id);
                                // console.log('new _this.area_codes', _this.area_codes)
                            }

                            _this.map.data.overrideStyle(event.feature, {
                                fillColor: '#F77000',
                                fillOpacity: 0.8
                            });

                            // Mark the clicked feature as selected
                            _this.selectedFeature = event.feature;
                            _this.selected_layer_id = layer_id;
                        } else {
                            _this.showToast(area_name + ' area is not yet active.', 'warning')
                        }
                    });

                    _this.map.data.addListener('mouseover', function (event) {
                        let layer_id = event.feature.getProperty('ID_3');
                        if (!_this.idExists(_this.service_areas, layer_id) && layer_id != _this.selected_layer_id) {
                            _this.map.data.overrideStyle(event.feature, {
                                fillColor: '#044CAC', // Change fill color on hover (red)
                                fillOpacity: 0.7
                            });
                        }

                        infowindow.setContent(event.feature.getProperty('NAME_3'));
                        infowindow.setPosition(event.latLng);
                        infowindow.open(_this.map);
                    });

                    _this.map.data.addListener('mouseout', function (event) {
                        let layer_id = event.feature.getProperty('ID_3');
                        if (!_this.idExists(_this.service_areas, layer_id) && layer_id != _this.selected_layer_id) {
                            _this.map.data.revertStyle(event.feature); // Revert to default style
                        }
                        infowindow.close();
                    });
                } else {
                    _this.map.data.addListener('click', function (event) {
                        let latLng = {
                            lat: event.latLng.lat(),
                            lng: event.latLng.lng()
                        }
                        console.log('event.latLng', latLng)
                        setMarker(_this.map, latlng);
                    });
                }
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

        async getBarangayByCoordinates(lat, lng) {

            await fetch(`${this.app_url}api/operational-areas/brgy-geojson`)
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
        async deleteAreaEvent(e, provider_id, area_code) {
            let _this = this;
            Swal.fire({
                title: "Please confirm",
                text: `You are sure do you want to delete this area code ${area_code}?`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes",
            }).then((result) => {
                if (result.isConfirmed) {
                    axios({
                        method: "DELETE",
                        url: `${_this.app_url}api/service-area`,
                        data: {
                            provider_id: provider_id,
                            area_code: area_code
                        },
                        headers: {
                            Authorization: `Bearer ${_this.user_token}`,
                        }
                    }).then(
                        (response) => {
                            console.log(response)
                            if (response.data.success) {
                                _this.showToast(response.data.message, 'success')
                            } else {
                                //  _this.showToast('Success', response.data.message, 'success')
                            }
                            _this.map.data.forEach(async function (feature) {
                                if (feature.getProperty('ID_3') == area_code) {
                                    deletedFeature = feature;
                                    _this.map.data.remove(feature); // Remove the feature (polygon)
                                    _this.service_areas = _this.removeById(_this.service_areas, area_code);

                                    // Trigger a custom 'deleted' event
                                    google.maps.event.trigger(map, 'polygondeleted', {
                                        feature: deletedFeature
                                    });
                                }
                            });
                        },
                        (error) => {
                            console.log(error);
                        }
                    );
                }
            });
        },
        async getActiveAreaCodes() {
            let _this = this;
            await fetch(`${_this.app_url}api/operational-areas/active-area-codes`).then((response) => {
                    if (response.ok) {
                        return response.json();
                    }
                    throw new Error('Something went wrong');
                }).then((responseJson) => {
                    _this.operational_area_codes = responseJson
                })
                .catch((error) => {
                    console.log(error)
                });
        },
        async zoomToLocation(area_code, zoomLevel = 12) {
            let _this = this;
            _this.map.data.forEach(function (feature) {
                if (feature.getProperty('ID_3') == area_code) {
                    var bounds = new google.maps.LatLngBounds();
                    feature.getGeometry().forEachLatLng(function (latlng) {
                        bounds.extend(latlng);
                    });

                    _this.map.fitBounds(bounds);
                    _this.map.setZoom(zoomLevel);
                }
            });
        },
        idExists(array, idToCheck) {
            return array.some(item => item.area_code == idToCheck);
        },
        removeById(array, idToRemove) {
            return array.filter(item => item.area_code != idToRemove);
        },
        removeItem(arr) {
            var what, a = arguments,
                L = a.length,
                ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax = arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
        },
        ucFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        formatDate(dateString) {
            const months = [
                "Jan", "Febr", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
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
        async eventListeners() {
            let _this = this;
            let mdl = $('#postModal')

            $(document).on('click', '.btn-add', function (e) {
                mdl.find('form').attr('action', `/admin/users/store`)
                _this.modal_title = "Add"
                _this.resetModel()
            });

            $(document).on('click', '.btn-edit', function (e) {
                _this.getDetails(this)
                _this.modal_title = "Edit"
                _this.id = $(this).attr('data-id');
                let mdl = $('#postModal');
                mdl.find('form').attr('action', `/admin/users/update/${_this.id}`)
            });
            1

            $(document).on('click', '.btn-wallet', function (e) {
                _this.id = $(this).attr('data-id');
                _this.wallet_balance = $(this).attr('data-wallet');

                _this.getTransactions()

                $('#walletModal').modal('show')
            });


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
                            url: `/admin/users/${id}`,
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
        _this.getActiveAreaCodes()
        const modal = document.getElementById('postModal');
        modal.addEventListener('shown.bs.modal', this.initializeMap);
        _this.eventListeners()
    },
});

app.mount("#app");

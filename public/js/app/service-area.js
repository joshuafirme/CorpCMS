const app = Vue.createApp({
    data() {
        console.log(window.Laravel)
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            success_msg: "",
            err_msg: "",
            form: {},
            map: null,
            barangay_layer: null,
            selectedFeature: null,
            service_areas: [],
            selected_layer_id: null,
            area_names: [],
            area_codes: [],
            operational_area_codes: [],
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
        idExists(array, idToCheck) {
            return array.some(item => item.area_code == idToCheck);
        },
        removeById(array, idToRemove) {
            return array.filter(item => item.area_code != idToRemove);
        },
        async save(event) {
            event.preventDefault();
            let _this = this;
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;

            event.target.innerHTML = `Saving &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;

            const form = document.getElementById("form");

            let formData = new FormData(form);

            let url = `${this.app_url}api/service-area`;

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
                            _this.success_msg = response.data.message;
                            _this.showToast(response.data.message, 'success')
                        } else {
                            _this.err_msg = response.data.message;
                            _this.showToast(response.data.message, 'error')
                        }
                        event.target.innerHTML = "Save changes";
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
        async getServiceAreas() {
            const provider_id = $('#provider_id').val();
            const res = await fetch(`${this.app_url}api/service-area/${provider_id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + this.user_token,
                }),
            });

            let data = await res.json();
            console.log('data', data)
            this.service_areas = data ? data : {};
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
        async initMap() {
            // Initialize the map
            let _this = this;


            _this.map = new google.maps.Map(document.getElementById('map'), {
                center: {
                    lat: 14.5995,
                    lng: 120.9842
                }, // Centered on Manila
                zoom: 12
            });

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

                _this.map.data.addListener('click', function (event) {
                    console.log(event)
                    let layer_id = event.feature.getProperty('ID_3');
                    let area_name = event.feature.getProperty('NAME_3');
                    if (_this.operational_area_codes.includes(layer_id.toString())) {

                        if (!_this.idExists(_this.service_areas, layer_id) && layer_id != _this.selected_layer_id) {
                            //  _this.map.data.revertStyle(_this.selectedFeature);
                            // Change the selected feature's style permanently

                            _this.service_areas.push({
                                area_name: area_name,
                                area_code: layer_id
                            });

                            _this.area_codes = _this.service_areas.map(a => a.area_code);
                            _this.area_names = _this.service_areas.map(a => a.area_name);
                        } else {
                            //   _this.map.data.remove(event.feature);
                        }

                        _this.map.data.overrideStyle(event.feature, {
                            fillColor: '#F77000',
                            fillOpacity: 0.8
                        });

                        // Mark the clicked feature as selected
                        _this.selectedFeature = event.feature;
                        _this.selected_layer_id = layer_id;
                    }
                    else {
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
            });


        },
        async zoomToLocation(area_code, zoomLevel = 12) {
            let _this = this;
            _this.map.data.forEach(function (feature) {
                // Check if the feature's area_code matches the one we are looking for
                if (feature.getProperty('ID_3') == area_code) {

                    // Get the bounds of the feature
                    var bounds = new google.maps.LatLngBounds();

                    // If the feature is a polygon, loop through its geometry and extend the bounds
                    feature.getGeometry().forEachLatLng(function (latlng) {
                        bounds.extend(latlng);
                    });
                    console.log('bounds', bounds)

                    // Fit the map to the bounds of the selected feature
                    _this.map.fitBounds(bounds);
                    _this.map.setZoom(zoomLevel); // Set maximum zoom level
                }
            });
        },
        async deleteAreaEvent(e, area_code) {
            let _this = this;
            const provider_id = $('#provider_id').val();
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
                                    _this.map.data.remove(feature);
                                    _this.service_areas = _this.removeById(_this.service_areas, area_code);

                                    google.maps.event.trigger(map, 'polygondeleted', {
                                        feature: feature
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
        showToast(message, icon = null) {
            $.toast({
                text: message,
                showHideTransition: 'slide',
                icon: icon,
                stack: 1,
                hideAfter: false,
                position: 'bottom-right',
            })
        }
    },
    async mounted() {
        await this.getServiceAreas()
        await this.getActiveAreaCodes()
        await this.initMap()
    },
});

app.mount("#app");

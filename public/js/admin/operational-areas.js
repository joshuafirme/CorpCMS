const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            modal_title: "Add",
            operational_area: null,
            region_choices: null,
            province_choices: null,
            municipality_choices: null,
            id: null,

            map: null,
            barangay_layer: null,
            selectedFeature: null,
            service_areas: [],
            selected_layer_id: null,
            area_names: [],
            area_codes: [],
            loading: "Loading map...",

            success_msg: null,
            err_msg: null,
        };
    },
    methods: {
        async getDetails(event) {
            let _this = this;

            let default_html = $(event).html();

            $(event).html(`<i class="fas fa-spinner fa-pulse"></i>`);

            let id = $(event).attr('data-id')

            const res = await fetch(`${_this.app_url}api/operational-areas/${id}`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();
            console.log('sdata', data)

            this.operational_area = data;

            $('#updateModal').modal('show');

            $(event).html(default_html);
        },
        async initializeChoices() {
            let _this = this;
            if (this.$refs.region_choices) {
                this.region_choices = new Choices(this.$refs.region_choices, {
                    allowHTML: true,
                    searchPlaceholderValue: 'Search a category',
                })

                this.region_choices.passedElement.element.addEventListener(
                    'change',
                    async function (event) {
                        let region = event.detail.value;
                        _this.populateProvinces(region)
                    }
                );
            }

            if (this.$refs.province_choices) {
                this.province_choices = new Choices(this.$refs.province_choices, {
                    allowHTML: true,
                    searchPlaceholderValue: 'Search a province',
                })

                this.province_choices.passedElement.element.addEventListener(
                    'change',
                    async function (event) {
                        let province = event.detail.value;

                        _this.populateMunicipalities(province)
                    }
                );
            }

            if (this.$refs.municipality_choices) {
                this.municipality_choices = new Choices(this.$refs.municipality_choices, {
                    allowHTML: true,
                    searchPlaceholderValue: 'Search a municipality',
                })
            }
        },

        async populateRegions() {

            let _this = this;

            const res = await fetch(`${_this.app_url}api/regions`);

            let data = await res.json();
            _this.region_choices.clearStore();
            _this.region_choices.setChoices(function () {
                let regions = [];
                Object.keys(data).forEach(function (key) {
                    //  console.log(key, data[key].region_name)
                    regions.push({
                        label: data[key].region_name,
                        value: key
                    })
                });
                console.log(regions)
                return regions;
            })
        },
        async populateProvinces(region) {
            let _this = this;
            const res = await fetch(`${_this.app_url}api/provinces/${region}`);

            let data = await res.json();

            _this.province_choices.clearStore();
            _this.province_choices.setChoices(function () {
                let provinces = [];
                Object.keys(data).forEach(function (key) {
                    //  console.log(key, data[key].province_name)
                    provinces.push({
                        label: key,
                        value: key
                    })
                });
                console.log(provinces)
                return provinces;
            })
        },
        async populateMunicipalities(province) {
            let _this = this;
            let region = _this.$refs.region_choices.value;
            if (_this.operational_area && _this.operational_area.region) {
                region = _this.operational_area.region;
            }
            const res = await fetch(`${_this.app_url}api/municipalities/${region}/${province}`);

            let data = await res.json();
            console.log('muni', data)

            _this.municipality_choices.clearStore();
            _this.municipality_choices.setChoices(function () {
                let municipalities = [];
                Object.keys(data).forEach(function (key) {
                    municipalities.push({
                        label: key,
                        value: key
                    })
                });
                console.log(municipalities)
                return municipalities;
            })
        },
        create() {
            $('#updateModal').modal('show');
            this.modal_title = "Add"
            this.region_choices.clearStore()
            this.province_choices.clearStore()
            this.municipality_choices.clearStore()
            this.populateRegions()
        },
        eventListeners() {
            let _this = this;
            $(document).on('click', '.btn-edit', async function (e) {
                _this.modal_title = "Edit"
                await _this.getDetails(this)
                _this.id = $(this).attr('data-id')
                $('#updateModal').find('form').attr('action', `/admin/operational-areas/update/${_this.id}`);
                console.log('_this.$refs.region_choices.value', _this.$refs.region_choices.value)
                console.log('_this.operational_area.region', _this.operational_area.region)
                await _this.populateProvinces(_this.operational_area.region)
                await _this.populateMunicipalities(_this.operational_area.province)
                _this.region_choices.setChoiceByValue(_this.operational_area.region);
                _this.province_choices.setChoiceByValue(_this.operational_area.province);
                _this.municipality_choices.setChoiceByValue(_this.operational_area.municipality);
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
                            url: `/admin/operational-areas/${id}`,
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
        },
        async initializeMap() {
            let _this = this;


            _this.map = new google.maps.Map(document.getElementById('map'), {
                zoom: 12
            });

            if (this.operational_area) {
                var request = {
                    query: this.operational_area.municipality,
                    fields: ['name', 'geometry'],
                };

                var service = new google.maps.places.PlacesService(_this.map);

                service.findPlaceFromQuery(request, function (results, status) {
                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                        //   for (var i = 0; i < results.length; i++) {
                        //     createMarker(results[i]);
                        //   }
                        console.log(results[0])
                        _this.map.setCenter(results[0].geometry.location);
                    }
                });
            }

            // Load the GeoJSON (barangay boundaries)
            await _this.map.data.loadGeoJson(`${this.app_url}api/operational-areas/brgy-geojson?operational_area_id=${_this.id}`, null, async function (features) {
                console.log('GeoJSON data loaded:', features.length + ' features added');
                // Perform any post-load actions here
                _this.loading = '';
                var infowindow = new google.maps.InfoWindow({
                    content: 'Your content here',
                    disableAutoPan: true, // Prevent auto-panning to the popup
                    pixelOffset: new google.maps.Size(0, -30) // Adjust popup position if needed
                });

                await fetch(`${_this.app_url}storage/municipality-active-area-codes/${_this.id}.json`).then((response) => {
                        if (response.ok) {
                            return response.json();
                        }
                        throw new Error('Something went wrong');
                    }).then((responseJson) => {
                        _this.area_codes = responseJson
                    })
                    .catch((error) => {
                        console.log(error)
                    });

                _this.map.data.setStyle(function (feature) {
                    var areaCode = feature.getProperty('ID_3');
                    if (_this.area_codes.includes(areaCode.toString())) {
                        // Return a style for active barangays
                        console.log('active', areaCode)
                        return {
                            fillColor: '#F77000',
                            fillOpacity: 0.7,
                            strokeColor: '#F77000', // Border color
                            strokeWeight: 0.4 // Border thickness
                        };
                    } else {
                        return {
                            fillColor: '#F77000', // Set to any color (not visible due to fillOpacity being 0)
                            fillOpacity: 0.3,
                            strokeColor: '#A44A00', // Border color
                            strokeWeight: 0.4 // Border thickness
                        };
                    }
                });

                _this.map.data.addListener('click', function (event) {
                    console.log(event)
                    let layer_id = event.feature.getProperty('ID_3').toString();
                    let province = event.feature.getProperty('NAME_1');
                    let municipality = event.feature.getProperty('NAME_2');
                    let area_name = event.feature.getProperty('NAME_3');

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

                        _this.area_codes.push(layer_id)
                        _this.area_names = _this.service_areas.map(a => a.area_name);
                    } else {
                        console.log('unselected area code', layer_id)
                        _this.map.data.overrideStyle(event.feature, {
                            fillColor: '#F77000',
                            fillOpacity: 0.3,
                            strokeColor: '#A44A00',
                            strokeWeight: 0.4
                        });
                        console.log('old _this.area_codes', _this.area_codes)
                        _this.area_codes = _this.removeItem(_this.area_codes, layer_id);
                        console.log('new _this.area_codes', _this.area_codes)
                    }


                    _this.selectedFeature = event.feature;
                    _this.selected_layer_id = layer_id;
                    console.log('_this.selected_layer_id', _this.selected_layer_id)
                });

                _this.map.data.addListener('mouseover', function (event) {
                    let layer_id = event.feature.getProperty('ID_3').toString();
                    if (!_this.area_codes.includes(layer_id)) {
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
                    let layer_id = event.feature.getProperty('ID_3').toString();
                    if (!_this.area_codes.includes(layer_id) && layer_id != _this.selected_layer_id) {
                        _this.map.data.revertStyle(event.feature); // Revert to default style
                    }
                    infowindow.close();
                });
            });
        },
        idExists(array, idToCheck) {
            return array.some(item => item.area_code == idToCheck);
        },
        removeById(array, idToRemove) {
            return array.filter(item => item.area_code != idToRemove);
        },
        removeItem(arr) {
            var what, a = arguments, L = a.length, ax;
            while (L > 1 && arr.length) {
                what = a[--L];
                while ((ax= arr.indexOf(what)) !== -1) {
                    arr.splice(ax, 1);
                }
            }
            return arr;
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
    },
    async mounted() {
        let _this = this;

        const modal = document.getElementById('updateModal');
        modal.addEventListener('shown.bs.modal', this.initializeMap);

        await _this.initializeChoices()
        await _this.populateRegions()

        _this.eventListeners()
    },
});

app.mount("#app");

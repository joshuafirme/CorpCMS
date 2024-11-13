const app = Vue.createApp({
    data() {
        console.log(window.Laravel)
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            services: [],
            selected_categories: [],
            selected_subcategories: [],
            selected_category_ids: [],
            selected_subcategory_ids: [],
            category_choices: null,
            success_msg: "",
            err_msg: "",
            form: {},
        };
    },
    methods: {
        async saveServices(event) {
            let _this = this;
            event.preventDefault();
            _this.success_msg = '';
            _this.err_msg = '';

            let btn_old_html = event.target.innerHTML;
            event.target.disabled = true;

            event.target.innerHTML = `Saving &nbsp; <i class="fas fa-circle-notch fa-spin ml-1"></i>`;

            const form = document.getElementById("postForm");

            let formData = new FormData(form);

            let url = `${this.app_url}api/service-provider/update-services`;

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
                        } else {
                            _this.err_msg = response.data.message;
                        }
                        event.target.innerHTML = btn_old_html;
                        event.target.disabled = false;
                    },
                    (error) => {
                        console.log(error)
                    }
            );
        },
        async subcategoryClick(subcategory, cat_index, sub_index) {

            let selected_item = $(`.subcat-index-${cat_index}-${sub_index}`);

            if (selected_item.hasClass('btn-subcat-selected')) {
                selected_item.removeClass('btn-subcat-selected')

                console.log('old this.selected_subcategories', this.selected_subcategories)
                this.selected_subcategories = this.removeById(this.selected_subcategories, subcategory.id)
                console.log('new this.selected_subcategories', this.selected_subcategories)
                
                this.selected_subcategory_ids = this.selected_subcategories.map(a => a.id);
            } else {

                selected_item.addClass('btn-subcat-selected')

                if (!this.selected_subcategory_ids.includes(subcategory.id)) {
                    this.selected_subcategories.push({
                        id: subcategory.id,
                        name: subcategory.name,
                    });
                    this.selected_subcategory_ids.push(this.selected_subcategories.map(a => a.id));
                }
            }

            console.log('this.selected_subcategories', this.selected_subcategories)
            console.log('this.selected_subcategory_ids', this.selected_subcategory_ids)
        },
        idExists(array, idToCheck) {
            return array.some(item => item.id === idToCheck);
        },
        removeById(array, idToRemove) {
            return array.filter(item => item.id !== idToRemove);
        },
        async getProviderServices() {
            let _this = this;

            const res = await fetch(`${_this.app_url}api/service-provider/services`, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let data = await res.json();
            console.log('data', data)
            _this.services = data ? data : [];

            for (const service of _this.services) {
                _this.selected_subcategories.push(service.subcategory)
                _this.selected_subcategory_ids.push(service.subcategory.id)
            }
        },
        async getCategories(ids = null) {
            let _this = this;
            let queryString = null;

            if (ids) {
                queryString = ids.map(id => `ids[]=${id}`).join('&');
            }

            const res = await fetch(`${_this.app_url}api/categories?${queryString}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            });

            let data = await res.json();
            console.log('categories', data)
            let categories = data ? data : [];

            if (_this.services.length > 0) {
                for (const category of categories) {
                    _this.selected_categories.push({
                        id: category.id,
                        name: category.name,
                        subcategories: category.subcategories
                    });
                }
                console.log('_this.selected_categories', _this.selected_categories)
            }
        },
        async loadChoices() {
            let _this = this;

            _this.category_choices = new Choices(
                '#category-choices', {
                    allowHTML: true,
                    delimiter: ',',
                    removeItemButton: true,
                    searchPlaceholderValue: 'Search category',
                    placeholder: true,
                    //  maxItemCount: 5,
                }
            );

            _this.category_choices.passedElement.element.addEventListener(
                'removeItem',
                function (event) {
                    console.log(event.detail)
                    console.log(_this.selected_categories)
                    let output = [];
                    for (const element of _this.selected_categories) {
                        if (event.detail.id != element.id) {
                            output.push(element)
                        }
                    }
                    _this.selected_categories = output;
                    _this.selected_category_ids = this.selected_categories.map(a => a.id);
                }
            );

            _this.category_choices.passedElement.element.addEventListener(
                'addItem',
                function (event) {
                    console.log(event.detail)

                    if (!_this.idExists(_this.selected_categories, event.detail.id)) {
                        _this.selected_categories.push({
                            id: event.detail.id,
                            name: event.detail.label,
                            subcategories: event.detail.customProperties
                        });
                    }

                    _this.selected_category_ids.push(event.detail.id)

                    console.log('_this.selected_categories', _this.selected_categories)
                }
            );
        },
        async validateForm() {
            let _this = this;
            let form = $("#signup-form");
            let rules = {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
            }
            console.log('rules', rules)
            let validation = form.validate({
                rules: rules,
                messages: {
                    confirm_password: {
                        equalTo: "Passwords do not match"
                    }
                }
            });
            console.log(validation)

            form.valid()
            if (validation.errorList == 0) {
                return true;
            }
            return false;
        },
    },
    async mounted() {
        this.getProviderServices();
        this.loadChoices();
        this.getCategories($('#category-choices').val());

    },
});

app.mount("#app");

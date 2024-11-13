const app = Vue.createApp({
    data() {
        console.log(window.Laravel)
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            recaptcha_key: window.Laravel.recaptcha_key,
            selected_categories: [],
            selected_subcategories: [],
            selected_category_ids: [],
            selected_subcategory_ids: [],
            preview_btn_class: 'btn-selected',
            step: 1,
            success_msg: "",
            err_msg: "",
            form: {},
            attachments: [],
        };
    },
    methods: {
        async nextStepClick(action) {
            if (action == 'next' && this.step < 3) {
                let is_valid = await this.validateForm();
                if (is_valid) {
                    if (this.step == 2 && this.selected_subcategories.length < 1) {
                        this.err_msg = 'No selected service subcategories';
                        return;
                    }
                    this.step++;
                }
            } else if (this.step > 0) {
                this.step--;
            }
            this.loadChoices();

            let formData = new FormData(document.getElementById("signup-form"));
            var object = {};
            formData.forEach(function (value, key) {
                object[key] = value;
            });
            var json = JSON.stringify(object);
            this.form = JSON.parse(json)
            console.log('form ', this.form)
        },
        async subcategoryClick(subcategory, cat_index, sub_index) {

            let selected_item = $(`.subcat-index-${cat_index}-${sub_index}`);

            if (selected_item.hasClass('btn-subcat-selected')) {
                selected_item.removeClass('btn-subcat-selected')

                this.selected_subcategories = this.removeById(this.selected_subcategories, subcategory.id)
                this.selected_subcategory_ids = this.selected_subcategories.map(a => a.id);
            } else {

                selected_item.addClass('btn-subcat-selected')

                this.selected_subcategories.push({
                    id: subcategory.id,
                    name: subcategory.name,
                });
                this.selected_subcategory_ids = this.selected_subcategories.map(a => a.id);
            }

            console.log('this.selected_subcategories', this.selected_subcategories)
            console.log('this.selected_subcategory_ids', this.selected_subcategory_ids)
        },
        async attachmentChange(event) {
            const files = event.target.files;
            let _this = this;
            if (files) {
                _this.attachments = [];
                for (const file of files) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        _this.attachments.push({
                            file_name: file.name,
                            blob: event.target.result
                        });
                    };
                    reader.readAsDataURL(file);
                }
            }
        },
        idExists(array, idToCheck) {
            return array.some(item => item.id === idToCheck);
        },
        removeById(array, idToRemove) {
            return array.filter(item => item.id !== idToRemove);
        },
        async loadChoices() {
            let _this = this;
            if (this.step == 2) {
                this.$nextTick(() => {
                    var multipleCancelButton = new Choices(
                        '#category-choices', {
                            allowHTML: true,
                            delimiter: ',',
                            removeItemButton: true,
                            searchPlaceholderValue: 'Search category',
                            placeholder: true,
                            //  maxItemCount: 5,
                        }
                    );

                    multipleCancelButton.passedElement.element.addEventListener(
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

                    multipleCancelButton.passedElement.element.addEventListener(
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

                });
            }
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
        async handleEvents() {
            let _this = this;


            $('#category-choices').on('change', function () {
                // Get the selected option
                const selectedOption = $(this).find(':selected');

                const selectedValue = selectedOption.val();

                const subcategories = selectedOption.data('custom-properties');

                // }
            });

            $(document).on('click', '#btn-submit', function (e) {

                e.preventDefault();
                let btn = $(this);
                btn.html('<i class="fas fa-circle-notch fa-spin"></i>')
                btn.prop('disabled', true)

                let is_valid = _this.validateForm()

                if (is_valid) {

                    grecaptcha.ready(function () {
                        grecaptcha.execute(_this.recaptcha_key, {
                            action: 'submit'
                        }).then(function (token) {

                            $('#signup-form').prepend(
                                '<input type="hidden" class="token" name="token" value="' +
                                token + '">');

                            setTimeout(() => {
                                $("#signup-form").submit()
                                btn.html('Sign up')
                                btn.prop('disabled', false)
                            }, 500);
                        });
                    });
                }
                else {
                    btn.prop('disabled', false)
                }


                return false;
            });


        },
    },
    async mounted() {
        await this.handleEvents();
    },
});

app.mount("#app");

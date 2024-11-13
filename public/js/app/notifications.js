const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            user: {},
            notifications: [],
            pagination: {},
            pages: [],
            success_msg: null,
            err_msg: null,
        };
    },
    methods: {
        async getNotifications(page = 1) {
            let _this = this;

            let url = `${_this.app_url}api/notifications?page=${page}&paginate=true`;

            const res = await fetch(url, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let response = await res.json();
            console.log('response', response)
            _this.notifications = response.data ? response.data.data : [];
            _this.pagination = response.data;
            _this.setPages();
        },
        setPages() {
            this.pages = [];
            for (let i = 1; i <= this.pagination.last_page; i++) {
                this.pages.push(i);
            }
        },
        timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);  // Difference in seconds
        
            // Define time intervals in seconds
            const intervals = {
                year: 365 * 24 * 60 * 60,
                month: 30 * 24 * 60 * 60,
                day: 24 * 60 * 60,
                hour: 60 * 60,
                minute: 60,
                second: 1,
            };
        
            // Loop through intervals and determine the largest unit
            for (let key in intervals) {
                const intervalInSeconds = intervals[key];
                const timePassed = Math.floor(diffInSeconds / intervalInSeconds);
        
                if (timePassed >= 1) {
                    return timePassed === 1 ? `${timePassed} ${key} ago` : `${timePassed} ${key}s ago`;
                }
            }
        
            return "just now";  // For very recent dates
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
        await this.getNotifications();
    },
});

app.mount("#app");

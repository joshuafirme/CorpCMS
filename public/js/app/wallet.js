const app = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            user_type: window.Laravel.user_type,
            user: {},
            transactions: [],
            service_details: null,
            star: 0,
            review: null,
            show_reason: false,
            show_remarks: false,
            pagination: {},
            status: '',
            pages: [],
            success_msg: null,
            err_msg: null,
        };
    },
    methods: {
        async getWalletTransactions(page = 1) {
            let _this = this;

            let url = `${_this.app_url}api/user/wallet-transactions/${_this.user_id}?page=${page}&paginate=true`;

            const res = await fetch(url, {
                headers: new Headers({
                    Authorization: "Bearer " + _this.user_token,
                }),
            });

            let response = await res.json();
            console.log('response', response)
            _this.user = response.user;
            _this.transactions = response.data ? response.data.data : [];
            _this.pagination = response.data;
            _this.setPages();
            console.log(' _this.user', _this.user)
        },
        setPages() {
            this.pages = [];
            for (let i = 1; i <= this.pagination.last_page; i++) {
                this.pages.push(i);
            }
        },
        onClickStatus(event) {
            this.status = event.target.getAttribute("data-value")
        },
        onChangeStatus(event) {
            if (event.target.value == 'cancelled') {
                this.show_reason = true;
            } else {
                this.show_reason = false;
            }
            if (event.target.value == 'completed') {
                this.show_remarks = true;
            } else {
                this.show_remarks = false;
            }
        },
        showChangeStatusModal(service_details) {
            $('#changeStatusModal').modal('show')
            this.service_details = service_details;
        },
        showRateModal(service_details) {
            $('#rateModal').modal('show')
            this.service_details = service_details;
        },
        timeAgo(dateString) {
            const date = new Date(dateString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000); // Difference in seconds

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

            return "just now"; // For very recent dates
        },
        formatDate(dateString, format = null) {
            const months = [
                "January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            const months_abv = [
                "Jan", "Feb", "Mar", "Apr", "May", "Jun",
                "Jul", "Aug", "Sept", "Oct", "Nov", "Dec"
            ];

            const date = new Date(dateString);

            const month = months_abv[date.getMonth()];
            const day = date.getDate();
            const year = date.getFullYear();

            let hours = date.getHours();
            const minutes = date.getMinutes().toString().padStart(2, '0');

            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12;
            let formattedDate = `${month} ${day}, ${year} ${hours}:${minutes} ${ampm}`;

            if (format == 'Y-m-d') {
                formattedDate = `${year}-${date.getMonth()}-${day} ${hours}:${minutes} ${ampm}`;
            }

            return formattedDate;
        },
        ucFirst(str) {
            return str.charAt(0).toUpperCase() + str.slice(1);
        },
        typeAttr(type) {
            if (type == 'credit') {
                return {
                    color: 'success',
                    operator: '+',
                    action: 'Received'
                }
            }
            return {
                color: 'danger',
                operator: '-',
                action: 'Deducted'
            }
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
        let _this = this;
        await this.getWalletTransactions();
        setTimeout(() => {
            document.querySelectorAll('.star').forEach(star => {
                star.addEventListener('click', function () {
                    _this.star = this.dataset.value;
                    console.log('_this.star', _this.star)
                    document.querySelectorAll('.star').forEach(s => s.classList.remove('active'));

                    // Highlight all stars up to the clicked one
                    for (let i = 0; i < _this.star; i++) {
                        document.querySelectorAll('.star')[i].classList.add('active');
                    }
                });
                star.addEventListener('mouseover', function () {
                    // Clear previous active stars
                    document.querySelectorAll('.star').forEach(s => s.classList.remove('active'));

                    // Highlight all stars up to the clicked one
                    for (let i = 0; i < this.dataset.value; i++) {
                        document.querySelectorAll('.star')[i].classList.add('active');
                    }
                });
                star.addEventListener('mouseout', function () {
                    // Clear previous active stars
                    document.querySelectorAll('.star').forEach(s => s.classList.remove('active'));

                    // Highlight all stars up to the clicked one
                    let stars = _this.star ? _this.star : 0;
                    for (let i = 0; i < stars; i++) {
                        document.querySelectorAll('.star')[i].classList.add('active');
                    }
                });
            });
        }, 300);
    },
});

app.mount("#app");

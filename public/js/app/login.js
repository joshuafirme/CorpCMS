// const app = Vue.createApp({
//     data() {
//         console.log(window.Laravel)
//         return {
//             app_url: window.Laravel.appUrl,
//             user_token: window.Laravel.user_token,
//             success_msg: "",
//             err_msg: "",
//         };
//     },
//     methods: {
//         async doLogin(event) {
//             let _this = this;
//             _this.success_msg = '';
//             _this.err_msg = '';

//             let btn_old_html = event.target.innerHTML;

//             event.target.innerHTML = `<i class="fas fa-circle-notch fa-spin ml-1"></i>`;

//             const form = document.getElementById("loginForm");

//             let formData = new FormData(form);

//             let url = `${this.app_url}login`;

//             await axios({
//                 method: "post",
//                 url: url,
//                 data: formData,
//             }).then(
//                 async (response) => {

//                         console.log(response)
//                         window.location.replace(`${_this.app_url}dashboard`)
//                     },
//                     (error) => {
//                         let err_res = error.response.data;
//                         let errors = err_res.errors;
//                         console.log(error)
//                         _this.err_msg = err_res.message ? err_res.message : '';
//                         if (errors) {
//                             for (var key of Object.keys(errors)) {
//                                 for (let index = 0; index < errors[key].length; index++) {
//                                     console.log(errors[key][index])
//                                     _this.err_msg += errors[key][index] + '<br>'
//                                 }
//                             }
//                         }
//                         event.target.innerHTML = btn_old_html;
//                     }
//             );
//         },
       
//         async handleEvents() {
//             let _this = this;

//             $(document).on("submit", "#loginForm", function (e) {
//                 e.preventDefault();
//                 return false;
//             });
//         },
//     },
//     async mounted() {
//         await this.handleEvents();
//     },
// });

// app.mount("#app");

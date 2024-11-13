const firebaseapp = Vue.createApp({
    data() {
        return {
            app_url: window.Laravel.appUrl,
            user_token: window.Laravel.user_token,
            user_id: window.Laravel.user_id,
            notif_title: "",
            notif_body: "",
        };
    },
    methods: {

        async saveDeviceToken(token) {
            let _this = this;
            let existing_token = localStorage.getItem('device_token');
          //  if (existing_token == null) {
                await axios({
                    method: "post",
                    url: `${this.app_url}api/notification/save-device-token`,
                    data: {
                        device_token: token,
                        user_id: _this.user_id
                    },
                    headers: {
                        Authorization: `Bearer ${_this.user_token}`
                    }
                }).then(
                    async (response) => {
                            console.log(response)
                            if (response.data.success) {
                                console.log('token saved', token)
                                localStorage.setItem('device_token', token);
                            }
                        },
                        (error) => {
                            console.log(error)
                        }
                );
          //  }
        },
        async initFirebase() {
            let _this = this;
            var firebaseConfig = {
                apiKey: "AIzaSyA5lkDguNnDQ9-zqq988BcpsOKKOhd72JY",
                authDomain: "maki-5ae03.firebaseapp.com",
                projectId: "maki-5ae03",
                storageBucket: "maki-5ae03.appspot.com",
                messagingSenderId: "875065941985",
                appId: "1:875065941985:web:d852d006d32b6d6625fca0"
            };

            firebase.initializeApp(firebaseConfig);
            messaging = firebase.messaging();

            messaging
                .requestPermission()
                .then(function () {
                    return messaging.getToken()
                })
                .then(async function (token) {
                    _this.saveDeviceToken(token);

                }).catch(function (err) {
                    console.log('User Chat Token Error' + err);
                });

            // Register the Service Worker
            if ('serviceWorker' in navigator) {
                navigator.serviceWorker.register('/firebase-messaging-sw.js')
                    .then((registration) => {
                        console.log('Service Worker registered with scope:', registration.scope);
                    })
                    .catch((err) => {
                        console.error('Service Worker registration failed:', err);
                    });
            }


            messaging.onMessage(function (payload) {

                console.log('payload', payload)
                const noteTitle = payload.notification.title;
                const noteOptions = {
                    body: payload.notification.body,
                    icon: payload.notification.icon,
                };

                if (payload.data && payload.data.action) {
                    _this.storeData('latestNotification', payload)
                        .then(() => console.log('[firebase-messaging-sw.js] Notification data stored!'))
                        .catch(err => console.error('Failed to store notification data', err));
                }

                $.toast({
                    heading: noteTitle,
                    text: payload.notification.body,
                    showHideTransition: 'slide',
                    icon: 'success',
                    stack: 1,
                    hideAfter: false,
                    position: 'bottom-right',
                })

                //console.log('fcm payload: ' + payload.notification)
                const notification = new Notification(noteTitle, noteOptions);

                // Play sound after the notification is displayed
                // notification.onclick = function () {
                //     var audio = new Audio(`${_this.app_url}assets/audio/mixkit-correct-answer-tone-2870.wav`);
                //     audio.play().catch(function (error) {
                //         console.error('Error playing sound:', error);
                //     });
                // };

                // // Optionally, play sound immediately when notification is received
                // var audio = new Audio(`${_this.app_url}assets/audio/mixkit-correct-answer-tone-2870.wav`);
                // audio.play().catch(function (error) {
                //     console.error('Error playing sound:', error);
                // });
            });
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
        async storeData(key, value) {
            return openDatabase().then(db => {
                const transaction = db.transaction(['firebase-data'], 'readwrite');
                const objectStore = transaction.objectStore('firebase-data');
                objectStore.put(value, key);
                return transaction.complete;
            });
        }
    },
    async mounted() {
        await this.initFirebase();
    },
});

firebaseapp.mount("#fcm");

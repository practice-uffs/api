/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { message } from 'laravel-mix/src/Log';
// window.Vue = require('vue');
import Vue from 'vue';

/* import the fontawesome core */
import { library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { faEye } from '@fortawesome/free-solid-svg-icons'
import { faEyeLowVision } from '@fortawesome/free-solid-svg-icons'
import { faXmark } from '@fortawesome/free-solid-svg-icons'
import { faEllipsisVertical } from '@fortawesome/free-solid-svg-icons'

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

 library.add(faEye, faEyeLowVision, faEllipsisVertical, faXmark)

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('message-component', require('./components/MessageComponent.vue').default);
Vue.component('consent-component', require('./components/ConsentComponent.vue').default);
Vue.component('header-component', require('./components/HeaderComponent.vue').default);
Vue.component('input-component', require('./components/InputComponent.vue').default);
Vue.component('login-component', require('./components/LoginComponent.vue').default);
Vue.component('font-awesome-icon', FontAwesomeIcon)

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


const app = new Vue({
    el: '#app',

    data: {
        messages: [{id: 1, message: "Olá! Eu me chamo aura, sua assistente virtual.", source: "aura", assessed: 2, userMessage: "has_no_message", category: "welcome_message"}],
        showConsentPopup: false,
        showHeader: true,
        showLogin: false,
        userTheme: "light-theme",
        userToken: "",
    },

    mounted() {
        this.setTheme(this.userTheme);
        this.getUserTokenFromUrl();        
    },
    methods: {
        toggleTheme() {
            const activeTheme = this.userTheme;
            if (activeTheme === "dark-theme") {
              this.setTheme("dark-theme");
            } else {
              this.setTheme("light-theme");
            }
        },
        setTheme(theme) {
            //localStorage.setItem("user-theme", theme); //TODO: Salvar para mais tarde
            this.userTheme = theme;
            document.documentElement.className = theme;
        },
        getMediaPreference() {
            const hasDarkPreference = window.matchMedia(
              "(prefers-color-scheme: dark)"
            ).matches;
            if (hasDarkPreference) {
              return "dark-theme";
            } else {
              return "light-theme";
            }
        },
        getUserTokenFromUrl() {
            const url = new URL(location.href);
            const urlToken = url.searchParams.get('token');

            if (urlToken) {
                axios({
                    method: "GET",
                    url: "/v0/user/",
                    headers: {
                        Authorization: `Bearer ${urlToken}`
                    }
                }).then(()=>{
                    this.userToken = urlToken;
                }).catch(() => this.showLogin = true);
            } else {
                this.showLogin = true;
            }
        },
    }
});

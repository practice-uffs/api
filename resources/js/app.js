/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import { message } from 'laravel-mix/src/Log';
// window.Vue = require('vue');
import Vue from 'vue';

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);
Vue.component('message-component', require('./components/MessageComponent.vue').default);
Vue.component('input-component', require('./components/InputComponent.vue').default);
Vue.component('header-component', require('./components/HeaderComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */


 
const app = new Vue({
    el: '#app',

    data: {
        messages: [{id: 1, message: "Olá! Eu me chamo aura, sua assistente virtual.", source: "aura", assessed: 2, userMessage: "has_no_message", category: "welcome_message"}],
        showHeader: true,
        userTheme: "light-theme",
        userToken: "",
    },

    mounted() {
        this.setTheme(this.userTheme);
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
    }
});

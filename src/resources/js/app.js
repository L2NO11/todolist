/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
require('./bootstrap')
import { createApp } from 'vue';
import router from './router';
import store from './store';
import BootstrapVue3 from "bootstrap-vue-3";
import App from './App.vue';
import "bootstrap/dist/css/bootstrap.css";
import "bootstrap-vue-3/dist/bootstrap-vue-3.css";

const app = createApp(App);
app.use(router);
app.use(BootstrapVue3);
app.use(store);
app.mount('#app');


/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

import {ServerTable}    from 'vue-tables-2';
import { ClientTable}   from 'vue-tables-2';
Vue.use(ServerTable, {}, false, 'bootstrap4', 'default');
Vue.use(ClientTable);

//VUE HTTP RESOURCE
import VueResource from 'vue-resource'
Vue.use(VueResource);
//.VUE HTTP RESOURCE

import StripeForm from './components/StripeForm';
Vue.component('stripe-form', StripeForm);

import Courses  from './components/Courses';
import Teachers from './components/Teachers';
import Paypal   from './components/Paypal';
Vue.component('courses-list', Courses);
Vue.component('teacher-list', Teachers);
Vue.component('paypal', Paypal);

const app = new Vue({
    el: '#app'
});

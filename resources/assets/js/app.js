
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

import {ServerTable, ClientTable, Event}    from 'vue-tables-2';
Vue.use(ServerTable, {}, false, 'bootstrap4', 'default');
Vue.use(ClientTable, {}, false, 'bootstrap4', 'default');


import moment from 'moment';
import { Form, HasError, AlertError } from 'vform';
import VTooltip from 'v-tooltip'
Vue.use(VTooltip)

import { IEXClient } from 'iex-api'
import * as _fetch from 'isomorphic-fetch'
const iex = new IEXClient(_fetch)
window.iex = iex;

import swal from 'sweetalert2'
window.swal = swal;

const toast = swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
  });

  window.toast = toast;


  window.Form = Form;
  Vue.component(HasError.name, HasError)
  Vue.component(AlertError.name, AlertError)

  Vue.component('pagination', require('laravel-vue-pagination'));

  import VueRouter from 'vue-router'
  Vue.use(VueRouter)

 import VueProgressBar from 'vue-progressbar'
 
  Vue.use(VueProgressBar, {
      color: 'rgb(143, 255, 199)',
      failedColor: 'red',
      height: '3px'
    })
 
//VUE HTTP RESOURCE
import VueResource from 'vue-resource'
Vue.use(VueResource);
//.VUE HTTP RESOURCE

import StripeForm from './components/StripeForm';
Vue.component('stripe-form', StripeForm);

import JsonExcel from 'vue-json-excel' 
Vue.component('downloadExcel', JsonExcel)

import Dashboard            from './components/Dashboard';
import Courses              from './components/Courses';
import Course               from './components/Course';
import Teachers             from './components/Teachers';
import Paypal               from './components/Paypal';
import Payu                 from './components/Payu';
import Content              from './components/Content';
import Coupon               from './components/Coupon';
import ShowVideo            from './components/ShowVideo';
import Grafica              from './components/Graficas';
import Student              from './components/Student';
import Student_Registered   from './components/StudentJustRegistered';
import Category             from './components/Category';
import StudentSubscribed    from './components/StudentSuscribed';
import Profile              from './components/Profile';
import TBook                from './components/TBook';
import TCompany             from './components/TCompany';
import TPrevious            from './components/TPrevious';
import TKeyStat             from './components/TKeyStat';
import CourseStudent        from './components/CourseStudent';
import PhotoProfile         from './components/PhotoProfile';
import PaypalPayment        from './components/PaypalPayment';
import UserPayment          from './components/UserPayment';
import PayuCheckOut         from './components/PayuCheckOut';
import Epay                 from './components/Epay';


let routes = [
    //{  name: 'dashboard',path: '/dashboard', component: require('./components/Dashboard.vue') },
    { path: '/'             , component: Dashboard },
    { path: '/dashboard'    , component: Dashboard },
    { path: '/courses'      , component: Courses },
    { path: '/course/:id'   , component: Course },
    { path: '/teachers'     , component: Teachers },
    { path: '/students'     , component: Student },
    { path: '/registered'   , component: Student_Registered },
    { path: '/paypal'       , component: Paypal },
    { path: '/epay'         , component: Epay },
    { path: '/payu'         , component: Payu },
    { path: '/user_payment' , component: UserPayment },
    { path: '/content/:id'  , component: Content },
    { path: '/coupon'       , component: Coupon },
    { path: '/profile'      , component: Profile },
    { path: '/video/:id/:course'    , component: ShowVideo },  
    { path: '/category'      , component: Category },
    { path: '/subscribed'    , component: StudentSubscribed },
    { path: '/quote'         , component: TBook },
    { path: '/company'       , component: TCompany },
    { path: '/previous'      , component: TPrevious },
    { path: '/keystat'       , component: TKeyStat },

  ]

const router = new VueRouter({
    mode: 'history',
    routes // short for `routes: routes`
  })


Vue.component('courses-list', Courses);
Vue.component('teacher-list', Teachers);
Vue.component('paypal', Paypal);
Vue.component('grafica', Grafica);
Vue.component('course-student', CourseStudent);
Vue.component('photo-profile', PhotoProfile);
Vue.component('paypal-payment', PaypalPayment);
Vue.component('payu-check-out', PayuCheckOut);

Vue.filter('myDate',function(created){
  return moment(created).format('MMMM Do YYYY');
});
window.Fire =  new Vue();

const app = new Vue({
    el: '#app',
    router,
});

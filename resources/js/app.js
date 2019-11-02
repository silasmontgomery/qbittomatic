// Config Options
var baseUrl = '/api/v1';
var cookieExpiration = '1d';

// Requirements
import Vue from 'vue';
import VueRouter from 'vue-router';
import VueCookies from 'vue-cookies';

Vue.use(VueRouter);
Vue.use(VueCookies);

// Vue Cookies Setup
VueCookies.config(cookieExpiration);

// Axios Setup
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.axios.defaults.baseURL = baseUrl;
window.fetchInterval = null;

// View single file components (views)
import App from './views/app.vue'
import Login from './views/login.vue'
import Dashboard from './views/dashboard.vue';

// Reusable components
Vue.component('menu-component', require('./components/menu.vue').default);

const routes = [
  { path: '/dashboard', name: 'dashboard', component: Dashboard },
  { path: '/login', name: 'login', component: Login },
]

const router = new VueRouter({
  mode: 'history',
  routes: routes
})

const app = new Vue({
  el: '#app',
  components: { App },
  router
});

// Now the app has started!
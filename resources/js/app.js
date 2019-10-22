import Vue from 'vue';
import VueRouter from 'vue-router';
import BootstrapVue from 'bootstrap-vue';
import PortalVue from 'portal-vue';
import Popper from 'popper.js';

Vue.use(VueRouter);
Vue.use(BootstrapVue);
Vue.use(PortalVue);

import Dashboard from './dashboard.vue';

const routes = [
    { path: '/', component: Dashboard },
]

const router = new VueRouter({
    mode: 'history',
    base: '/',
    routes: routes
})

const app = new Vue({
  router
}).$mount('#app')

// Now the app has started!
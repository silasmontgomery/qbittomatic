import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

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
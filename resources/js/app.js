import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import Menu from './menu.vue';
import Dashboard from './dashboard.vue';

Vue.component('menu-component', {
  Menu
});

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
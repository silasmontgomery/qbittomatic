import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

const Foo = { template: '<div>Foo!</div>' }
const Bar = { template: '<div>Bar!</div>' }

const routes = [
  { path: '/foo', component: Foo },
  { path: '/bar', component: Bar }
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
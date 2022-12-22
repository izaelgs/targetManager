
require('./bootstrap');

window.Vue = require('vue').default;

import App from './components/App';
import VueRouter from 'vue-router';
import routes from './router/routes';
import store from './store/index';

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    components: {App},
    store,
    router: new VueRouter(routes)
});

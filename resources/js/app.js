
require('./bootstrap');

window.Vue = require('vue').default;

import VueRouter from 'vue-router';
import App from './components/App';

import routes from './router/routes';
import i18n from './i18n';
import store from './store/index';

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    components: {App},
    store,
    i18n,
    router: new VueRouter(routes)
});

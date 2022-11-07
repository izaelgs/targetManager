
require('./bootstrap');

window.Vue = require('vue').default;

import App from './components/App';
import VueRouter from 'vue-router';
import routes from './router/routes';

Vue.use(VueRouter);

const app = new Vue({
    el: '#app',
    components: {App},
    router: new VueRouter(routes)
});

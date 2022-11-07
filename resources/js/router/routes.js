import Home from '../pages/Home';
import NotFound from '../pages/NotFound';
import categories from '../pages/categories';
import Target from '../pages/Target';
import CreateTarget from '../pages/CreateTarget';
import Login from '../pages/Login';

import Guard from '../services/middleware';

export default {
    mode: 'history',
    linkActiveClass: 'fw-bolder',

    routes: [
        {
            path: '*',
            name: 'notfound',
            component: NotFound,
        },
        {
            path: '/',
            name: 'home',
            component: Home,
        },
        {
            path: '/login',
            name: 'login',
            component: Login,
        },
        {
            path: '/categories',
            name: 'categories',
            component: categories,
        },
        {
            path: '/target/:id',
            name: 'show-target',
            component: Target,
        },
        {
            path: '/new-target',
            name: 'new-target',
            component: CreateTarget,
            beforeEnter: Guard.auth
        },
    ]
}

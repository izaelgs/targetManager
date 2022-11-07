import ExampleComponent from '../components/ExampleComponent';
import Home from '../components/Home';
import NotFound from '../components/NotFound';

export default {
    mode: 'history',
    linkActiveClass: 'fw-bolder',

    routes: [
        {
            path: '*',
            component: NotFound,
            name: 'notfound'
        },
        {
            path: '/',
            component: Home,
            name: 'home'
        },
        {
            path: '/example',
            component: ExampleComponent,
            name: 'example'
        },
    ]
}

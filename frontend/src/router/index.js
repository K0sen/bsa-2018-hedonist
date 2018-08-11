import Vue from 'vue';
import Router from 'vue-router';
import PlacePage from '@/pages/PlacePage';
import ProfilePage from '@/pages/ProfilePage';
import ReviewList from '@/components/review/ReviewList';
import PlacesList from  '@/components/PlacesList/PlacesList';
import store from '../store/index';
import middlewares from './middlewares';
import SignUp from '@/components/auth/SignUp';
import UserListAdd from '@/components/user-list/UserListAdd';
import SignUpPage from '@/pages/SignUpPage';
import LoginPage from '@/pages/LoginPage';
import ResetPasswordPage from '@/pages/ResetPasswordPage';
import RecoverPasswordPage from '@/pages/RecoverPasswordPage';
import TastesAdd from '@/components/tastes/TastesAdd';

Vue.use(Router);

const middleware = handler => (
    routes => routes.map(route => Object.assign({}, route, { beforeEnter: handler }))
);

export default new Router({
    mode: 'history',
    base: '/',
    scrollBehavior: () => ({y: 0}),
    routes: [
        ...middleware(middlewares.guest(store))([
            {
                path: '/',
                name: 'home',
                redirect: '/search'
            },
            {
                path: '/profile',
                name: 'ProfilePage',
                component: ProfilePage,
            },
            {
                path: '/places/list',
                name: 'PlacesList',
                component: PlacesList
            },
            {
                path: '/place-info',
                name: 'PlacePage',
                component: PlacePage
            },
            {
                path: '/reviews',
                name: 'ReviewList',
                component: ReviewList
            },
            {
                path: '/user-list/add',
                name: 'UserListAdd',
                component: UserListAdd
            },
            {
                path: '/tastes/add',
                name: 'Tastes',
                component: TastesAdd
            }
        ]),
        ...middleware(middlewares.guest(store))([
            {
                path: '/login',
                name: 'LoginPage',
                component: LoginPage
            },
            {
                path: '/signup',
                name: 'SignUp',
                component: SignUpPage
            },
            {
                path: '/reset',
                name: 'ResetPasswordPage',
                component: ResetPasswordPage
            },
            {
                path: '/recover',
                name: 'RecoverPasswordPage',
                component: RecoverPasswordPage
            },
        ])
    ]
});

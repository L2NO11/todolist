import {createRouter, createWebHistory } from 'vue-router'
import store from '@/store'
const routes = [
    {
        name: "login",
        path: "/login",
        component:  () => import('@/page/LoginPage.vue'),
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component:  () => import('@/page/RegisterPage.vue'),
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: '/',
        name: 'dashboard',
        component: () => import('@/page/HomePage.vue'),
        meta: {
            middleware: "auth:api",
            title: `Dashboard`
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notfound',
        component: () => import('@/page/PathNotFound.vue')
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.authenticated) {
            next({ name: "dashboard" })
        }
        next()
    } else {
        if (store.state.authenticated) {
            next()
        } else {
            next({ name: "login" })
        }
    }
})

export default router;

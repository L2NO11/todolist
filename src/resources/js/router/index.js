import {createRouter, createWebHistory } from 'vue-router'
import store from '../store'
const routes = [
    {
        name: "login",
        path: "/login",
        component:  () => import('../page/LoginPage.vue'),
        meta: {
            middleware: "guest",
            title: `Login`
        }
    },
    {
        name: "register",
        path: "/register",
        component:  () => import('../page/RegisterPage.vue'),
        meta: {
            middleware: "guest",
            title: `Register`
        }
    },
    {
        path: '/home',
        name: 'home',
        component: () => import('../page/HomePage.vue'),
        children: [
            {
                path: 'todolist',
                name: "todo-list",
                component: () => import('../components/ListComponent.vue'),
            },
            {
                path: 'create',
                name: "create",
                component: () => import('../components/CreateComponent.vue'),
            }
        ],
        meta: {
            title: "Home",
            middleware: "auth:api",
        }
    },
    {
        path: '/:pathMatch(.*)*',
        name: 'notfound',
        component: () => import('../page/PathNotFound.vue'),
        meta: {
            title: "Not Found",
        }
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

router.beforeEach((to, from, next) => {
    document.title = to.meta.title
    if (to.meta.middleware == "guest") {
        if (store.state.authenticated){
            next({name: "todo-list"})
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

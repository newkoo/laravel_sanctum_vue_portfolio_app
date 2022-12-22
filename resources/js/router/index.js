import { createRouter, createWebHistory } from 'vue-router'
import admin_routes from './Admin';
import public_routes from './Public';
import NotFound from '../Pages/NotFound.vue';
import Login from '../Pages/Auth/Login.vue';
import { RouteGenerator } from '../Utils/routing_tool';
import { getUserLocalInfo } from '../Utils/auth_handler';
const routes = [
    admin_routes,
    ...public_routes,
    RouteGenerator('', '/:pathMatch(.*)*', NotFound),
    RouteGenerator('', '/login', Login)
];

const router = createRouter({
    history: createWebHistory(),
    routes,
    linkExactActiveClass:"nav__active"
});

router.beforeEach((to, from) => {
    let token = getUserLocalInfo("token");
    let requiresAuth = to.meta.requiresAuth;
    if (requiresAuth && !token) return { path: "/login" }
    if (!requiresAuth && token) return { path: '/Admin/home' }
})

export default router;

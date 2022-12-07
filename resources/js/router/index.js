import { createRouter, createWebHistory } from 'vue-router'
import admin_routes from './Admin';
import public_routes from './Public';
import NotFound from '../Pages/NotFound.vue';
import Login from '../Pages/Auth/Login.vue';
import { RouteGenerator } from '../Utils/routing_tool';
const routes = [
    ...admin_routes,
    ...public_routes,
    RouteGenerator('', '/:pathMatch(.*)*', NotFound),
    RouteGenerator('', '/login', Login)
];

const router = createRouter({
    history: createWebHistory(),
    routes
});
export default router;

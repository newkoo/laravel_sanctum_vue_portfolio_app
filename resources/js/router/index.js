import { createRouter, createWebHistory } from 'vue-router'
import admin_routes from './Admin';
import public_routes from './Public';
import NotFound from '../Pages/not_found.vue';
const routes = [
    ...admin_routes,
    ...public_routes,
];

console.log(routes);

routes.push({
    path: '/pathMatch(.*)*',
    component: NotFound
});


console.log(routes);
const router = createRouter({
    history: createWebHistory(),
    routes
});
export default router;

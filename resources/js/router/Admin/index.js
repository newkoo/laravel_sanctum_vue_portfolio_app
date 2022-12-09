import { RouteGenerator } from "../../Utils/routing_tool";
import Wrapper from '../../Pages/Admin/Layouts/Wrapper.vue';
import Home from "../../Pages/Admin/Home/index.vue";
import About from "../../Pages/Admin/About/index.vue";
import Services from "../../Pages/Admin/Services/index.vue";
const pfx = "Admin";
const routes = RouteGenerator("Admin", "", Wrapper, true, [
    RouteGenerator("", "Home", Home, true),
    RouteGenerator("", "About", About, true),
    RouteGenerator("", "Services", Services, true)
])
export default routes;

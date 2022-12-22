import { RouteGenerator } from "../../Utils/routing_tool";
import Wrapper from '../../Pages/Admin/Layouts/Wrapper.vue';
import Home from "../../Pages/Admin/Home/index.vue";
import About from "../../Pages/Admin/About/index.vue";
import Services from "../../Pages/Admin/Services/index.vue";
import Skills from "../../Pages/Admin/Skills/index.vue";
import Educations from "../../Pages/Admin/Educations/index.vue";
import Experiences from "../../Pages/Admin/Experiences/index.vue";
import Projects from "../../Pages/Admin/Projects/index.vue";
import ProjectAdd from "../../Pages/Admin/Projects/add.vue";
import ProjectEdit from "../../Pages/Admin/Projects/edit.vue";
const pfx = "Admin";
const routes = RouteGenerator("Admin", "", Wrapper, true, [
    RouteGenerator("", "Home", Home, true),
    RouteGenerator("", "About", About, true),
    RouteGenerator("", "Services", Services, true),
    RouteGenerator("", "Skills", Skills, true),
    RouteGenerator("", "Educations", Educations, true),
    RouteGenerator("", "Experiences", Experiences, true),
    RouteGenerator("", "Projects", Projects, true,),
    RouteGenerator("", "Projects/add", ProjectAdd, true,),
    RouteGenerator("", "Projects/:id", ProjectEdit, true,),
])
export default routes;

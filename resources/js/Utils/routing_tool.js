/**
 * @file Route Generator Tooling For VueRouter
 * @version 0.0.2
 * @author Berke Güleç <berke.ggulec@gmail.com>
 */

/**
 * @typedef {"Public"|"Admin"|""} RouteType
 */

/**
 *
 * @param {RouteType} type
 * @param {*} url_path
 * @param {*} component_path
 * @param {Boolean} urlPrefix
 * @returns
 */
const RouteGenerator = (type, url_path, component_path, childrenRoutes = []) => {
    let component, data;
    type == "Admin" ? url_path = `/${type}/${url_path}` : null;
    typeof component_path === "string"
        ? (component_path = `../Pages/${type}/${component_path}.vue`, component = () => import(/* @vite-ignore */component_path))
        : (component = component_path, component_path = component.__file.split('resources/js/')[1])

    data = { path: url_path, component: component };

    childrenRoutes.length
        ? (data.children = childrenRoutes)
        : (data.name = component_path.replace(/.vue|\..\/Pages\//g, "").split('/').map((e) => e.charAt(0).toUpperCase() + e.slice(1)).join(""));

    return data;
}
export { RouteGenerator }

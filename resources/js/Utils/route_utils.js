/**
 * @typedef {"Public"|"Admin"} RouteType
 */

/**
 *
 * @param {RouteType} type
 * @param {*} url_path
 * @param {*} component_path
 * @param {Boolean} urlPrefix
 * @returns
 */
const RouteGenerator = (type, url_path, component_path, urlPrefix = true, childrenRoutes = []) => {
    if (urlPrefix)
        url_path = `/${type}/${url_path}`;
    component_path = `../Pages/${type}/${component_path}.vue`;

    let data = {
        path: url_path,
        component: () => import(/* @vite-ignore */component_path),
    };
    if (childrenRoutes.length)
        data.children = childrenRoutes;
    else
        data.name = component_path.replace('../Pages/', '').replace('.vue', '').split('/').map((e) => e.charAt(0).toUpperCase() + e.slice(1)).join("");

    return data;
}


export {
    RouteGenerator
}

export const setUserInfo = (token, name) => {
    localStorage.setItem('token', token)
    localStorage.setItem('name', name)
};
/**
 *
 * @param {"token"|"name"} key
 * @returns
 */
export const getUserLocalInfo = (key) => {
    return localStorage.getItem(key);
}
export const clearUserInfo = () => {
    let keys = [
        'token',
        'name'
    ];
    keys.forEach(element => {
        localStorage.removeItem(element)
    });
};

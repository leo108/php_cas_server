/**
 * Created by leo108 on 16/9/2.
 */
module.exports = (route, param) => {
    let routeUrl = Laravel.routes;
    let pathArr = route.split('.');
    for (let x in pathArr) {
        let path = pathArr[x];
        if (!routeUrl[path]) {
            return false;
        }
        routeUrl = routeUrl[path];
    }

    let append = [];

    for (let x in param) {
        let search = '{' + x + '}';

        if (routeUrl.indexOf(search) >= 0) {
            routeUrl = routeUrl.replace('{' + x + '}', param[x]);
        } else {
            append.push(x + '=' + param[x]);
        }
    }

    let url = '/' + _.trimStart(routeUrl, '/');

    if (append.length == 0) {
        return url;
    }

    if (url.indexOf('?') >= 0) {
        url += '&';
    } else {
        url += '?';
    }

    url += append.join('&');

    return url;
};

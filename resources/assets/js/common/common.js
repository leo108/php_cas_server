window._ = require('lodash');

window.$ = window.jQuery = require('jquery');

window.Laravel = $.extend({
    routes: {},
    lang: {},
    csrfToken: ''
}, window.Laravel ? window.Laravel : {});

if (!window.Laravel.csrfToken) {
    window.Laravel.csrfToken = $('meta[name=csrf-token]').attr('content');
}

require('bootstrap-sass');

window.Vue = require('vue');
require('vue-resource');

Vue.http.interceptors.push((request, next) => {
    request.headers.set('X-CSRF-TOKEN', Laravel.csrfToken);
    next();
});

Laravel.router = require('./backend-router-generator');
Laravel.trans = require('./translator');
require('./errors');

/**
 * 用户模块插件
 */
(function (Vue) {

    "use strict";
    var tokenKey = 'adminToken',
        User = {
            install: function (Vue) {
                Vue.prototype.$user = this;
                Vue.$user = this;
            },
            getId: function () {
                return Vue.cookies.get('adminId');
            },
            getName: function () {
                return Vue.cookies.get('adminName');
            },
            getAccessToken: function () {
                return Vue.cookies.get(tokenKey);
            },
            setInfo: function (id, name, token, expires) {
                Vue.cookies.set('adminId', id, expires);
                Vue.cookies.set('adminName', name, expires);
                Vue.cookies.set(tokenKey, token, expires);
            },
            clear: function () {
                Vue.cookies.remove('adminId', '/');
                Vue.cookies.remove('adminName', '/');
                Vue.cookies.remove(tokenKey, '/');
            },
            setLastUrl: function (url) {
                Vue.cookies.set('loginBackUrl', url);
            },
            getLastUrl: function () {
                Vue.cookies.get('loginBackUrl');
            }
        };

    if (typeof exports == "object") {
        module.exports = User;
    } else if (typeof define == "function" && define.amd) {
        define([], function () {
            return User;
        })
    } else if (window.Vue) {
        Vue.use(User);
    }


}(Vue));
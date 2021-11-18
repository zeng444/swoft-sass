(function (Vue, axios) {
    "use strict";
    var axois = {
        install: function (Vue) {
            Vue.prototype.$http = axios;
            axios.defaults.headers.post['Content-Type'] = 'application/x-www-form-urlencoded';
            axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
            var token = Vue.$user.getAccessToken();
            if (token) {
                axios.defaults.headers.common['Access-Token'] = token;
            }
            axios.interceptors.request.use(function (config) {
                Vue.prototype.$Loading.start();
                // Do something before request is sent
                return config;
            });
            axios.interceptors.response.use(function (response) {
                var body = response.data;
                if (response.data.code !== "200") {
                    Vue.prototype.$Loading.error();
                    if (response.data.code === '401' || response.data.code === '403') {
                        Vue.prototype.$Modal.confirm({
                            title: '错误提示',
                            content: response.data.message,
                            onOk: function () {
                                if (response.data.code === '401') {
                                    Vue.$user.setLastUrl(location.href);
                                    location.href = "/";
                                }
                            },
                            cancelText: null
                        });
                    }
                } else {
                    Vue.prototype.$Loading.finish();
                }
                return body;
            });
        },
    };
    Vue.use(axois);
}(Vue, axios));
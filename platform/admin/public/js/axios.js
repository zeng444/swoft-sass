(function(a,b){"use strict";var c={install:function(a){a.prototype.$http=b;b.defaults.headers.post["Content-Type"]="application/x-www-form-urlencoded";b.defaults.headers.common["X-Requested-With"]="XMLHttpRequest";var c=a.$user.getAccessToken();if(c){b.defaults.headers.common["Access-Token"]=c}b.interceptors.request.use(function(b){a.prototype.$Loading.start();return b});b.interceptors.response.use(function(b){var c=b.data;if(b.data.code!=="200"){a.prototype.$Loading.error();if(b.data.code==="401"||b.data.code==="403"){a.prototype.$Modal.confirm({title:"错误提示",content:b.data.message,onOk:function(){if(b.data.code==="401"){a.$user.setLastUrl(location.href);location.href="/"}},cancelText:null})}}else{a.prototype.$Loading.finish()}return c})}};a.use(c)})(Vue,axios);
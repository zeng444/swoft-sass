(function () {
    "use strict";
    new Janfish_DataPost({
        el: '#app',
        data: {
            tips: "",
            postMode: true,
            circles: [],
            successTips: '登录成功',
            api: {
                post: '/index'
            },
            formData: {
                username: "administrator",
                password: "",
                remember: '0'
            },
            validation: {
                username: [
                    {required: true, message: '姓名不能为空', trigger: 'blur'}
                ],
                password: [
                    {required: true, message: '密码不能为空', trigger: 'blur'}
                ]
            }
        },
        methods: {
            qr: function () {
                var _this = this, heartBeat, heartBeatIntervalTime = 15000, backUrl,
                    url = 'ws://socket.janfish.cn:8292/',
                    socket = new WebSocket(url);
                _this.tips = '二维码生成中...';
                _this.onerror = false;
                socket.onopen = function (evt) {
                    socket.send('{"EVENT":"Login","DATA":{"verify_api":"http://' + document.domain + '/qr/verify","bind_api":"http://' + document.domain + '/qr/bind"}}');
                };
                socket.onerror = function (evt) {
                    _this.onerror = true;
                    _this.tips = '连接失败，请尝试<span onclick="vue.qr()">重新</span>连接';
                    clearInterval(heartBeat);
                    socket = null;
                };
                socket.onclose = function (evt) {
                    if (_this.onerror === false) {
                        clearInterval(heartBeat);
                        socket = null;
                        _this.tips = '二维码超时失效，请<span onclick="vue.qr()">重新</span>生成';
                    }
                };
                socket.onmessage = function (evt) {
                    if (evt.data) {
                        var res = JSON.parse(evt.data), typeNumber = 7, errorCorrectionLevel = 'L', qr, qrEle, authUrl;
                        if (!res.data) {
                            return false;
                        }
                        res = res.data;
                        if (res.EVENT === 'Login' && res.ACTION === 'Response') {
                            //console.info(res);
                            _this.$user.setInfo(res.DATA.id, res.DATA.name, res.DATA.access_token, null);
                            _this.tips = '验证通过，登陆中.....';
                            setTimeout(function () {
                                backUrl = _this.$user.getLastUrl(location.href);
                                location.href = backUrl ? backUrl : '/';
                            }, 1200);
                        } else if (res.EVENT === 'Login' && res.ACTION === 'Index') {
                            qr = qrcode(typeNumber, errorCorrectionLevel);
                            qrEle = document.getElementById('qr-image');
                            authUrl = 'http://token.janfish.cn/wechat/authorize?state=wechatsso&scope=snsapi_userinfo&token=' + res.DATA.uid;
                            qr.addData(authUrl);
                            qr.make();
                            qrEle.innerHTML = qr.createImgTag(8, 0);
                            _this.tips = '请使用微信扫一扫登录';
                        }
                    }
                };
                heartBeat = setInterval(function () {
                    if (socket && socket.readyState == 1) {
                        socket.send('{"EVENT":"health","ACTION":"pong"}');
                    } else {
                        clearInterval(heartBeat);
                    }
                }, heartBeatIntervalTime);
            },
            afterPost: function (data) {
                var _this = this, backUrl;
                if (data['id'] && data['access_token']) {
                    this.$user.setInfo(data['id'], data['name'], data['access_token'], (this.formData.remember == '1') ? 7776000 : null);
                }
                setTimeout(function () {
                    backUrl = _this.$user.getLastUrl(location.href);
                    location.href = backUrl ? backUrl : data['jump_url'];
                }, 400);
            }
        },
        mounted: function () {
            if (this.$user.getAccessToken()) {
                location.href = this.$user.getLastUrl() ? this.$user.getLastUrl() : "/main";
                return false;
            }
            this.qr();
        }
    });
}());
(function (Vue, Qs) {


    window.appConfig = {
        imagePrefix: 'http://demo.cn/files/'
    };

    var janfish = Vue.extend({
        data: function () {
            return {
                purl: {
                    param: ''
                },
                adminName: '',
                adminId: '',
                version: '1.21.3',
                build: '20190524',
                iconSize: 20,
                activeMenu: [],
                searchKeyword: ''
            }
        },
        methods: {
            setActiveMenu: function (menu) {
                if (menu === false) {
                    return false;
                }
                this.activeMenu = menu;
                this.$nextTick(function () {
                    this.$refs.menu.updateOpened();
                    this.$refs.menu.updateActiveName();
                });
            },
            menuSearch: function () {
                if (!this.searchKeyword) {
                    this.$Message.error('查询内容不能为空!');
                    return false;
                }
                location.href = '/menu?keyword=' + encodeURIComponent(this.searchKeyword);
            },
            logout: function () {
                this.$user.clear();
                location.href = '/';
            }

        },
        created: function () {
            var _this = this;
            if (!_this.adminName) {
                _this.adminName = _this.$user.getName();
            }
            if (!_this.adminId) {
                _this.adminId = _this.$user.getId();
            }
            if (!_this.purl.param) {
                _this.purl.param = purl(true).param();
            }
        }
    });

    window.qs = Qs;
    window.Janfish = janfish;

}(Vue,Qs));
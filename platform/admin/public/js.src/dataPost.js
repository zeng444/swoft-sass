(function () {
    "use strict";
    var dataPost;
    dataPost = Janfish.extend({
        data: function () {
            return {
                successTips: '提交成功',
                api: {
                    post: ''
                },
                formData: {},
                validation: {},
                postMode: false,
                isNew: false
            }
        },
        methods: {
            uploadSuccess: function (response) {
                if (response.code != '200') {
                    this.$Message.error(response.message);
                    return false;
                }
                this.formData[response.data.field] = response.data.data[0].name;
            },
            dataLoad: function (id) {
                var _this = this, data, connect = _this.api.post.indexOf('?') ? '&' : '?';
                _this.$http.get(_this.api.post + connect + 'id=' + encodeURI(id)).then(function (response) {
                    if (response.status !== "success") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    data = _this.formatLoadedData(response.data.data);
                    _this.formData = _this.afterLoad(data);
                    _this.formLoaded = true;
                });
            },
            formatLoadedData: function (data) {
                var i, j;
                for (i in this.validation) {
                    for (j = 0; j < this.validation[i].length; j++) {
                        if (this.validation[i][j].type && ( this.validation[i][j].type.toLowerCase() == 'integer' || this.validation[i][j].type.toLowerCase() == 'number')) {
                            data[i] = Number(data[i]);
                            //console.info(i+' '+this.validation[i][j].type);
                        }
                    }
                }
                return data;
            },
            afterPost: function (data) {
                location.href = '/' + (this.api.post).split('/')[1];
            },
            afterLoad: function (data) {
                return data;
            },
            beforePost: function (data) {
                return data;
            },
            handleSubmit: function (name) {
                var _this = this;
                _this.$refs[name].validate(function (valid) {
                    if (valid) {
                        _this.$http.post(_this.api.post, qs.stringify(_this.beforePost(_this.formData))).then(function (response) {
                            if (response.status !== "success") {
                                _this.$Message.error(response.message);
                                return false;
                            }
                            _this.$Message.success(_this.successTips);
                            _this.afterPost(response.data);
                        });

                    } else {
                        _this.$Message.error('表单验证失败!');
                    }
                });
            },
            handleReset: function (name) {
                this.$refs[name].resetFields();
            }
        },
        mounted: function () {
            var _this = this;
            if (!_this.purl.param.id) {
                _this.isNew = true;
            }
            if (_this.postMode === false) {
                _this.dataLoad(_this.purl.param.id);
            }

        }
    });

    window.Janfish_DataPost = dataPost;
}());

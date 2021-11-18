(function () {
    "use strict";
    var DataList, i;
    DataList = Janfish.extend({
        data: function () {
            return {
                api: {
                    search: '',
                    remove: ''
                },
                primaryId: 'id',
                showPost: false,  //控制快速编辑
                formData: {}, //快速编辑数据
                showPreview: false, //控制预览窗
                previewData: {}, //预览数据
                selections: [], //批量选择中的ITEM
                count: 0, //记录总数
                page: 1, //当前页
                pageSize: 10, //显示记录数
                searchFilter: {}, //筛选器容器
                sortRule: '',
                columns: [], //定义table columns
                data: [] //定义table数据
            }
        },
        methods: {
            handleSubmit: function (name) {
                var _this = this;
                if (_this.formLoaded === false) {
                    return false;
                }
                _this.$refs[name].validate(function (valid) {
                    if (valid) {
                        _this.$http.post(_this.api.post, qs.stringify(_this.beforePost(_this.formData))).then(function (response) {
                            if (response.status !== "success") {
                                _this.$Message.error(response.message);
                                return false;
                            }
                            _this.$Message.success('提交成功');
                            _this.afterPost(response.data);
                            _this.handleReset(name);
                            _this.loadList(1, _this.pageSize);
                        });

                    } else {
                        _this.$Message.error('表单验证失败!');
                    }
                });
            },
            handleReset: function (name) {
                for (var i in this.formData) {
                    this.formData[i] = '';
                }
                if (this.showPost) {
                    this.showPost = false;
                }
                this.formLoaded = false;
                this.$refs[name].resetFields();
            },
            openPostModal: function (id) {
                var _this = this, data, connect = _this.api.post.indexOf('?') ? '&' : '?';
                _this.$http.get(_this.api.post + connect + 'id=' + encodeURI(id || '')).then(function (response) {
                    if (response.status !== "success") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    data = _this.formatLoadedData(response.data.data);
                    _this.formData = _this.afterLoad(data);
                    _this.formLoaded = true;
                });
                this.showPost = true;
            },
            displayImg: function (h, url) {
                return h('img', {
                    attrs: {
                        src: url
                    },
                    style: {
                        width: '45px',
                        margin: '5px 0'
                    }
                })
            },
            displayPreview: function (row) {
                this.previewData = row;
                this.showPreview = !this.showPreview;
            },
            afterPost: function (data) {

            },
            afterLoad: function (data) {
                return data;
            },
            beforePost: function (data) {
                return data;
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
            modifyRemoteData: function (row) {
                var _this = this;
                _this.$http.post(_this.api.post, qs.stringify(row)).then(function (response) {
                    if (response.status !== "success") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    _this.$Message.success('操作成功');
                });
            },
            remoteSort: function (rule) {
                if (rule.order === 'normal') {
                    this.sortRule = '';
                } else {
                    this.sortRule = rule.order === 'desc' ? ('-' + rule.key) : rule.key
                }
                this.loadList(1, this.pageSize);
            },
            selectionChange: function (id) {
                this.selections = id;
            },
            resetFilter: function () {
                for (i in this.searchFilter) {
                    this.searchFilter[i] = '';
                }
            },
            afterDataLoad: function (data) {
                return data;
            },
            listPostData: function (data) {
                return data;
            },
            loadList: function (page, pageSize) {
                var _this = this, api;
                _this.page = page;
                _this.pageSize = pageSize;
                _this.searchFilter.page = page;
                _this.searchFilter.pageSize = pageSize;
                api = _this.api.search;
                if (_this.sortRule) {
                    api += ((/\?/).test(_this.api.search) ? '&' : '?' ) + 'sort=' + encodeURI(_this.sortRule);
                }
                _this.$http.post(api, qs.stringify(_this.listPostData(_this.searchFilter))).then(function (response) {
                    if (response.code !== "200") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    var data = _this.afterDataLoad(response.data);
                    _this.data = data.data;
                    _this.count = data.count;
                });
            },
            pageChange: function (page) {
                this.loadList(page, this.pageSize);
            },
            sizeChange: function (size) {
                this.pageSize = size;
                this.loadList(1, this.pageSize);
            },
            remoteBehavior: function (api, id, msg) {
                msg = msg || '您确定要操作Id为 {{id}} 的记录吗';
                var _this = this;
                if (!_this.api[api]) {
                    console.info('remoteBehavior 没有定义接口行为');
                    return false;
                }
                this.$Modal.confirm({
                    title: '提示信息',
                    //content: '您确定要删除Id为 <strong>' + id + '</strong> 的记录吗',
                    content: msg.replace('{{id}}', '<strong>' + id + '</strong>'),
                    onOk: function () {
                        _this.$http.post(_this.api[api], qs.stringify({id: id})).then(function (response) {
                            if (response.code !== "200") {
                                _this.$Message.error(response.message);
                                return false;
                            }
                            _this.$Message.success("操作成功");
                            _this.loadList(_this.page, _this.pageSize);
                        });
                    },
                    onCancel: function () {
                        return false;
                    }
                });
            },
            batchRemoteBehavior: function (api, msg) {
                msg = msg || '您确定要操作Id为 {{id}} 的记录吗';
                var _this = this, id = [];
                if (!_this.api[api]) {
                    console.info('remoteBehavior 没有定义接口行为');
                    return false;
                }
                if (_this.selections.length == 0) {
                    _this.$Message.error("你没有任何选择");
                    return false;
                }
                for (i in _this.selections) {
                    id.push(_this.selections[i][_this.primaryId]);
                }
                this.$Modal.confirm({
                    title: '提示信息',
                    //content: '您确定要删除Id为 <strong>' + id.join('</strong>,<strong>') + '</strong> 的记录吗',
                    content: msg.replace('{{id}}', '<strong>' + id.join('</strong>,<strong>') + '</strong>'),
                    onOk: function () {
                        _this.$http.post(_this.api[api], qs.stringify({id: id.join(',')})).then(function (response) {
                            if (response.code !== "200") {
                                _this.$Message.error(response.message);
                                return false;
                            }
                            _this.selections = [];
                            _this.$Message.success("操作成功");
                            _this.loadList(_this.page, _this.pageSize);
                        });
                    },
                    onCancel: function () {
                        return false;
                    }
                });
            },
            remove: function (id) {
                this.remoteBehavior('remove', id, '您确定要删除Id为 {{id}} 的记录吗');
            },
            removeBatch: function () {
                this.batchRemoteBehavior('remove', '您确定要删除Id为 {{id}} 的记录吗');
            }
        },
        mounted: function () {
            this.loadList(this.page, this.pageSize);
        }
    });
    window.Janfish_DataList = DataList;
}());

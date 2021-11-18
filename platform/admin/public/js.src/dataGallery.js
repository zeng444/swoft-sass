(function () {
    "use strict";
    var DataGallery, i;
    DataGallery = Janfish.extend({
        data: function () {
            return {
                api: {
                    search: '',
                    post: '',
                    remove: ''
                },
                saveDefaultAttributes: {},
                columnSpan: 3,
                displayAttributes: {
                    sourceImage: 'image',
                    preview: 'thumbnail',
                    otherField: ["id"]
                },
                editMode: false,
                selectAllState: false,
                selectAllCheckbox: false,
                count: 0, //记录总数
                page: 1, //当前页
                pageSize: 10, //显示记录数
                searchFilter: {}, //筛选器容器
                data: [], //定义table数据
                previewModal: {
                    show: false,
                    title: "",
                    url: ""
                },
                uploadModal: {
                    show: false,
                    title: "批量上传"
                },
                successTips: '提交成功',
                uploadedFiles: [],
                dataChanged: false
            }
        },
        watch: {
            data: {
                handler: function () {
                    if (this.editMode) {
                        this.dataChanged = true;
                    }
                },
                deep: true
            }
        },
        methods: {
            __arrayMerge: function (array1, array2) {
                var key;
                for (key in array2) {
                    array1[key] = array2[key];
                }
                return array1;
            },
            beforePost: function (data) {
                return data;
            },
            closeUploadPanel: function () {
                this.uploadedFiles = [];
                this.$refs.upfile.clearFiles();
            },
            postNewData: function () {
                var _this = this;
                if (_this.uploadedFiles.length == 0) {
                    return false;
                }
                _this.$http.post(_this.api.post, JSON.stringify(_this.beforePost(_this.uploadedFiles))).then(function (response) {
                    if (response.status !== "success") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    _this.uploadedFiles = [];
                    _this.$Message.success(_this.successTips);
                    _this.closeUploadPanel();
                    _this.loadList(1, _this.pageSize);
                });
            },
            afterDataLoad: function (data) {
                for (i in data.data) {
                    data.data[i].checked = false;
                }
                return data;
            },
            upload: function () {
                this.uploadModal.show = true;
            },
            preview: function (item) {
                this.previewModal.show = true;
                this.previewModal.title = this.displayAttributes.otherField[0] ? item[this.displayAttributes.otherField[0]] : '图片预览';
                if (item[this.displayAttributes.sourceImage]) {
                    this.previewModal.url = appConfig.imagePrefix + item[this.displayAttributes.sourceImage];
                }
            },
            pickItems: function () {
                this.selectAllState = !this.selectAllState;
                for (i in this.data) {
                    this.data[i].checked = this.selectAllState;
                }
            },
            pickItem: function (item) {
                if (this.editMode) {
                    item.checked = !item.checked;
                }
            },
            remoteSave: function () {
                var _this = this;
                if (this.dataChanged) {
                    _this.$http.post(_this.api.post, JSON.stringify(_this.data)).then(function (response) {
                        if (response.status !== "success") {
                            _this.$Message.error(response.message);
                            return false;
                        }
                        _this.$Message.success(_this.successTips);
                        _this.selectAllCheckbox = false;
                        _this.selectAllState = false;
                        _this.dataChanged = false;
                        _this.switchMode();
                        _this.afterPost();
                    });
                } else {
                    _this.selectAllCheckbox = false;
                    _this.selectAllState = false;
                    _this.dataChanged = false;
                    _this.switchMode();
                }

            },
            switchMode: function () {
                this.editMode = !this.editMode;
                for (i in this.data) {
                    this.data[i].checked = false;
                }
            },
            resetFilter: function () {
                for (i in this.searchFilter) {
                    this.searchFilter[i] = '';
                }
            },
            afterPost: function () {

            },
            onUploadSuccess: function (response) {
                if (response.status === 'success') {
                    var row = {};
                    row[this.displayAttributes.sourceImage] = response.data.data[0].name;
                    row[this.displayAttributes.preview] = response.data.data[0].name;
                    for (i in this.displayAttributes.otherField) {
                        row[this.displayAttributes.otherField[i]] = response.data.data[0].name;
                    }
                    this.uploadedFiles.push(this.__arrayMerge(row, this.saveDefaultAttributes));
                }
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
            removeBatch: function () {
                var _this = this, id = [];
                for (i in this.data) {
                    if (this.data[i].checked === true) {
                        id.push(this.data[i].id);
                    }
                }
                if (id.length == 0) {
                    _this.$Message.error("你没有任何选择");
                    return false;
                }

                this.$Modal.confirm({
                    title: '提示信息',
                    content: '您确定要删除Id为 <strong>' + id.join('</strong>,<strong>') + '</strong> 的记录吗',
                    onOk: function () {
                        _this.$http.post(_this.api.remove, qs.stringify({id: id})).then(function (response) {
                            if (response.code !== "200") {
                                _this.$Message.error(response.message);
                                return false;
                            }
                            _this.selectAllState = false;
                            _this.selectAllCheckbox = false;
                            _this.$Message.success("操作成功");
                            _this.loadList(_this.page, _this.pageSize);
                        });
                    },
                    onCancel: function () {
                        return false;
                    }
                });
            }
        },
        mounted: function () {
            this.loadList(this.page, this.pageSize);
        }
    });
    window.Janfish_DataGallery = DataGallery;
}());

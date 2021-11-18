(function () {

    "use strict";

    Vue.component('i-ajax-cascader', Janfish.extend({
        props: ['value','placeholder', 'max-level', 'clearable',  'model', 'attribute', 'conditions', 'relation-attribute'],
        data: function () {
            return {
                options: [],
                level: 0
            }
        },
        render: function (h) {
            var _this = this;
            _this.maxLevel = !_this.maxLevel ? 2 : _this.maxLevel;
            _this.clearable = _this.clearable === undefined ? true : _this.clearable;
            _this.placeholder = _this.placeholder === undefined ? '请选择' : _this.placeholder;
            return h('Cascader', {
                on: {
                    "on-change": function (val) {
                        _this.$emit("input", val.splice(( _this.maxLevel - 1), 1));
                    }
                },
                props: {
                    data: _this.options,
                    placeholder: _this.placeholder,
                    clearable: _this.clearable,
                    "load-data": function (item, callback) {
                        var i;
                        item.loading = true;
                        _this.loadOptions(item.value, function (options) {
                            if (_this.level < _this.maxLevel) {
                                for (i in options) {
                                    options[i].loading = false;
                                    options[i].children = [];
                                }
                            }
                            item.children = options;
                            item.loading = false;
                            callback();
                        })
                    }
                }
            });
        },
        methods: {
            loadOptions: function (parentId, callback) {
                var _this = this,
                    model = this.model || '',
                    attribute = this.attribute || '',
                    relationAttribute = this.relationAttribute || 'parent_id',
                    conditions = this.conditions || {time: (+new Date())};
                if (!model || !attribute) {
                    _this.$Message.error('参数错误');
                    return false;
                }
                conditions = conditions ? JSON.stringify(conditions) : '';
                parentId = parentId || '';
                _this.$http.post('/resource/cascader', qs.stringify({relation_attribute: relationAttribute, model: model, attribute: attribute, parent_id: parentId, conditions: conditions})).then(function (response) {
                    if (response.status !== "success") {
                        _this.$Message.error(response.data.message);
                        return false;
                    }
                    _this.level++;
                    callback(response.data.data);
                });
            }
        },
        mounted: function () {
            var _this = this, i;
            _this.loadOptions('', function (options) {
                for (i in options) {
                    options[i].loading = false;
                    options[i].children = [];
                }
                _this.options = options;
            });
        }
    }));

}());
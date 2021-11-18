(function () {
    "use strict";
    Vue.component('i-ajax-select', Janfish.extend({
        props: ['model', 'attribute', 'conditions', 'value', 'filterable', 'multiple', 'remote-filterable', 'placeholder', 'clearable', 'label', 'disabled', 'url', 'size'],
        data: function () {
            return {
                options: [],
                loading: false
            }
        },
        render: function (h) {

            var i, options = [], _this = this, props, checkModel = this.url === undefined;
            for (i in this.options) {
                options.push(h('Option', {
                    props: {
                        value: this.options[i].id,
                        key: this.options[i].id,
                        label: this.options[i].name
                    }
                }, this.options[i].name));
            }
            _this.url = (this.url === undefined) ? '/resource/selector' : _this.url;
            _this.filterable = (this.filterable === undefined) ? false : this.filterable;
            _this.disabled = (this.disabled === undefined) ? false : this.disabled;
            _this.multiple = (this.multiple === undefined) ? false : this.multiple;
            _this.clearable = (this.clearable === undefined) ? false : this.clearable;
            _this.remoteFilterable = (this.remoteFilterable === undefined) ? false : this.remoteFilterable;
            props = {
                clearable: _this.clearable,
                disabled: _this.disabled,
                placeholder: _this.placeholder ? _this.placeholder : '请选择',
                filterable: _this.remoteFilterable === true ? _this.remoteFilterable : _this.filterable,
                multiple: _this.multiple,
                size: _this.size,
                remote: _this.remoteFilterable,
                loading: _this.remoteFilterable === true ? _this.loading : null,
                "remote-method": _this.remoteLoad,
                value: (typeof this.value !== 'object' ) ? ( ( this.value !== undefined) ? this.value.toString() : '' ) : this.value
            };
            if (_this.remoteFilterable === true) {
                props.label = _this.label;
            }
            return h('Select', {
                on: {
                    "on-change": function (val) {
                        _this.$emit('input', val);
                    }
                },
                props: props
            }, options);

        },
        methods: {
            remoteLoad: function (query) {
                //if (this.remoteFilterable && !query) {
                //    return false;
                //}
                var _this = this,
                    model = this.model || '',
                    attribute = this.attribute || '',
                    conditions = this.conditions || {time: (+new Date())};
                if (_this.checkModel === true) {
                    if (!model || !attribute) {
                        _this.$Message.error('参数错误');
                        return false;
                    }
                }
                if (query && typeof query == 'string') {
                    conditions[attribute] = query;
                }
                conditions = conditions ? JSON.stringify(conditions) : '';
                _this.loading = true;
                _this.options = [];
                _this.$http.post(_this.url, qs.stringify({model: model, attribute: attribute, conditions: conditions})).then(function (response) {
                    _this.loading = false;
                    if (response.status !== "success") {
                        _this.$Message.error(response.message);
                        return false;
                    }
                    _this.options = response.data.data;
                });
            }
        },
        mounted: function () {
            //if (this.remoteFilterable === false) {
            this.remoteLoad();
            //}
        }
    }));
}());
(function () {
    "use strict";

    function syleCal(val) {
        if ((/^\d+$/).test(val)) {
            return val + 'px';
        }
        return val;
    }

    Vue.component('i-location-input', Janfish.extend({
        props: ['value', 'width', 'height', 'placeholder', 'panControl', 'zoomControl', 'zoom', 'defaultPosition'],
        data: function () {
            return {
                map: "",
                selectedMarker: '',
                searchMarker: [],
                searchVal: '',
                searchService: '',
            }
        },
        watch: {
            value: function (val) {
                if (!val) {
                    return false;
                }
                var latLng = this.syncIn(val);
                if (this.selectedMarker) {
                    this.selectedMarker.setMap(null);
                }
                this.selectedMarker = new qq.maps.Marker({
                    position: new qq.maps.LatLng(latLng[0], latLng[1]),
                    map: this.map
                });
                this.map.setCenter(new qq.maps.LatLng(latLng[0], latLng[1]));
            }

        },
        render: function (h) {
            var _this = this, style = {
                height: '350px',
                width: '100%'
            };
            if (this.height) {
                style.height = syleCal(this.height);
            }
            if (this.width) {
                style.width = syleCal(this.width);
            }
            return h('Row', {}, [
                h('i-input', {
                    on: {
                        "input": function (val) {
                            _this.$emit('input', val)
                        }
                    },
                    style: {
                        marginBottom: '15px'
                    },
                    props: {
                        value: _this.value,
                        placeholder: this.placeholder
                    }
                }),
                h('div', {
                    'attrs': {
                        class: 'map'
                    },
                    style: style
                }, [
                    h("div", {
                        style: {
                            display: "flex",
                            padding: '8px',
                            position: "absolute",
                            left: 0,
                            top: 0,
                            zIndex: 1,
                            width: '100%',
                            backgroundColor: 'rgba(0,0,0,.2)'
                        }
                    }, [
                        h('input', {
                            style: {
                                marginRight: '10px',
                                height: '32px',
                                display: "inline-block",
                                width: '100%',
                                border: '1px solid #dddee1',
                                backgroundColor: '#fff',
                                color: '#495060',
                                cursor: 'text',
                                padding: '4px 7px',
                                lineHeight: '1.5',
                                borderRadius: '4px'

                            },
                            attrs: {
                                placeholder: '请输入带有城市名的地址 如:e.g:"成都方糖小镇"',
                                value: _this.searchVal
                            },
                            on: {
                                input: function (event) {
                                    _this.searchVal = event.target.value;
                                }
                            }
                        }),
                        h('i-button', {
                            props: {
                                type: "primary"
                            },
                            on: {
                                click: function () {
                                    _this.searchMap(_this.searchVal)
                                }
                            }
                        }, '搜索'),
                    ])
                ])
            ]);
        },
        methods: {
            syncIn: function (val) {
                //实现模版
                return val.split(',');
            },
            sync: function (lat, lng) {
                //实现模版
                this.$emit('input', lat + ',' + lng);
            },
            searchMap: function (val) {
                this.searchService.setPageIndex(0);
                this.searchService.setPageCapacity(10);
                this.searchService.search(val);
            },
            initMap: function () {
                var _this = this, box = this.$el.getElementsByClassName('map'),
                    center = new qq.maps.LatLng(30.657420, 104.065840);
                if (this.defaultPosition) {
                    this.defaultPosition = this.defaultPosition.split(',');
                    center = new qq.maps.LatLng(this.defaultPosition[0], this.defaultPosition[1]);
                }
                this.zoom = this.zoom === undefined ? 12 : this.zoom;
                this.map = new qq.maps.Map(box[0], {
                    zoom: this.zoom,
                    mapTypeControl: false,
                    zoomControl: this.zoomControl,
                    panControl: this.panControl,
                    zoomControlOptions: {
                        //设置缩放控件的位置相对右上角对齐，向下排列
                        position: qq.maps.ControlPosition.RIGHT_BOTTOM
                    },
                    panControlOptions: {
                        //设置缩放控件的位置相对右上角对齐，向下排列
                        position: qq.maps.ControlPosition.RIGHT_BOTTOM

                    },
                    center: center
                });


                qq.maps.event.addListener(
                    this.map,
                    'click',
                    function (event) {
                        var lat = event.latLng.getLat().toFixed(6), lng = event.latLng.getLng().toFixed(6);
                        _this.value = lat + ',' + lng;
                        _this.sync(lat, lng);
                    }
                );
                _this.searchService = new qq.maps.SearchService({
                    error: function (e) {
                        _this.$Message.error('出错了');
                    },
                    complete: function (results) {
                        if (results.type !== 'POI_LIST') {
                            _this.$Message.error('请附带城市信息比如:"成都方糖小镇"');
                            return false;
                        }
                        var poi, i, l, latlngBounds = new qq.maps.LatLngBounds(), pois = results.detail.pois, overlay;
                        while (overlay = _this.searchMarker.pop()) {
                            overlay.setMap(null);
                        }
                        for (i = 0, l = pois.length; i < l; i++) {
                            poi = pois[i];
                            //扩展边界范围，用来包含搜索到的Poi点
                            latlngBounds.extend(poi.latLng);
                            _this.searchMarker[i] = new qq.maps.Marker({
                                map: _this.map
                            });
                            _this.searchMarker[i].setPosition(poi.latLng);
                            _this.searchMarker[i].setTitle(poi.name);
                            (function (poi) {
                                qq.maps.event.addListener(_this.searchMarker[i], 'click', function () {
                                    var lat = poi.latLng.lat.toFixed(6), lng = poi.latLng.lng.toFixed(6);
                                    _this.value = lat + ',' + lng;
                                    _this.sync(lat, lng);
                                });
                            }(poi));
                        }
                        _this.map.fitBounds(latlngBounds);
                    },
                });
            }
        },
        mounted: function () {

            this.$nextTick(function () {
                this.initMap();
            });

        }
    }));
}());
(function () {
    "use strict";
    Vue.component('bubble', Janfish.extend({
        template: '<div class="circles"><div class="circle" v-for="item in circles" :style="item"></div></div>',
        props: ['seed-number'],
        data: function () {
            return {
                circles: []
            }
        },
        methods: {
            genCircle: function () {
                this.seedNumber = this.seedNumber ? this.seedNumber : 20;
                var i = 0, quantity = 10 + Math.ceil(Math.random() * (this.seedNumber)) + Math.ceil(Math.random() * 20);
                while (i < quantity) {
                    i++;
                    var size = (Math.ceil(Math.random() * ( i < 3 ? 1200 : 150))),
                        animation = (Math.ceil(Math.random() * 20)),
                        duration = 25 + (Math.ceil(Math.random() * 25)),
                        atomization = 5 + (Math.ceil(Math.random() * 18));
                    this.circles.push({
                        opacity: (Math.ceil(Math.random() * 25)) / 100,
                        left: (Math.ceil(Math.random() * 98)) + '%',
                        top: (Math.ceil(Math.random() * 98)) + '%',
                        background: "#1e85dc",
                        "box-shadow": "0 0 " + atomization + "px 12px #1e85dc",
                        width: size + 'px',
                        height: size + 'px',
                        animation: "float" + animation + " " + duration + "s infinite linear",
                        "-webkit-animation": "float" + animation + " " + duration + "s infinite linear"
                    })
                }
            }
        },
        mounted: function () {
            this.genCircle();
        }

    }));
}());
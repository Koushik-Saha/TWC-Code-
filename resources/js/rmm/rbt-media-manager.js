require('./bootstrap');

window.Vue = require('vue');

import VeeValidate from 'vee-validate';
Vue.use(VeeValidate);

import Toasted from 'vue-toasted';
Vue.use(Toasted, {
    position: 'bottom-right',
    duration: 2500,
    className: 'rbt-toast',
    containerClass: 'rbt-toast-container',
    iconPack: 'fontawesome',
});

import resize from 'vue-resize-directive';

Vue.component('rbt-media-manager', require('./RbtMediaManager').default);

const app = new Vue({
    el: '#app',
});

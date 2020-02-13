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

import Datepicker from 'vuejs-datepicker';
export default {
    // ...
    components: {
        Datepicker
    }
    // ...
}

import resize from 'vue-resize-directive';
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.component('rbt-media-manager', require('./rmm/RbtMediaManager').default);
Vue.component('income-data-table', require('./components/IncomeDataTable').default);
Vue.component('expense-data-table', require('./components/ExpensesDataTable').default);
Vue.component('transactions-data-table', require('./components/TransactionsDataTable').default);

const app = new Vue({
    el: '#app',
});

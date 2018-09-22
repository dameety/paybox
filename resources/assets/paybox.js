window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

import VueSweetAlert from 'vue-sweetalert';
Vue.use(VueSweetAlert);
Vue.prototype.$http = axios;


Vue.component('pb-invoices', require('./components/pb-invoices.vue'));
Vue.component('pb-interface', require('./components/pb-interface.vue'));
Vue.component('pb-subscription', require('./components/pb-subscription.vue'));
Vue.component('pb-cancel-subscription', require('./components/pb-cancel-subscription.vue'));
Vue.component('pb-payment-information', require('./components/pb-payment-information.vue'));
Vue.component('pb-plan-features', require('./components/pb-plan-features.vue'));

window.payboxEvent = new Vue();
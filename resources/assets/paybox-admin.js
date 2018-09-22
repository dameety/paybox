window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}

import VueSweetAlert from 'vue-sweetalert';

window.Vue = require('vue');
Vue.use(VueSweetAlert);
Vue.prototype.$http = axios;

Vue.component('user-invoices', require('./components/backend/user-invoices.vue'));
Vue.component('plan-features', require('./components/backend/plan-features.vue'));

const app = new Vue({
    el: '#app',

    data () {
        return {
            //data for user-invoices component
            userId: '',
            userInvoicesModal: false,

            //data for plan-features component
            planFeatures: '',
            showPlanFeaturesModal: false
        }
    },

    methods: {

        deleteError(slug) {
            const vm = this;
            this.$swal({
                title: 'Are you sure?',
                text: 'THis action cannot be reversed.',
                type: 'warning',
                showCancelButton: true,
            }).then((response) => {
                vm.$http.delete(`/ajax/error/${slug}/delete`).then((response) => {
                    location.reload();
                })
                .catch(error => {
                    this.errorResponse(error.response.data.error);
                });
            }).catch (() => { });
        },

        deletePlan (slug) {
            const vm = this;
            this.$swal({
                title: 'Are you sure?',
                text: 'This action cannot be reverted',
                type: 'warning',
                showCancelButton: true,
            }).then((response) => {
                vm.$http.delete(`/ajax/plan/${slug}/delete`).then((response) => {
                    location.reload();
                })
                .catch(error => {
                    this.errorResponse(error.response.data.error);
                });
            })
            .catch(() => {});
        },

        viewUserInvoices(id) {
            this.userId = id;
            this.userInvoicesModal = true;
        },

        viewPlanFeatures(plan) {
            this.planFeatures = plan.features;
            this.showPlanFeaturesModal = true;
        },

        errorResponse(message) {
            this.$swal({
                title: 'Something went wrong!',
                text: message,
                type: 'error'
            }).catch(() => {});
        }
    }
})
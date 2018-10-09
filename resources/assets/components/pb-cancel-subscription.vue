<template>

    <button class="uk-button uk-button-small uk-button-danger"
        @click.prevent="cancelSubscription()" v-if="showButton">

        <div class="loader" v-if="loading"></div>

        <span v-if="! loading">
            Cancel
        </span>

    </button>

</template>

<script>
    export default {

        data () {
            return {
                loading: false,
                showButton: false,
                isCancelled: false,
                hasSubscription: false
            }
        },

        created () {
            this.getCancelStatus();
        },

        mounted () {
            payboxEvent.$on('paybox-subscription-cancelled', () => {
                this.getCancelStatus();
            });
            payboxEvent.$on('paybox-subscription-created', () => {
                this.getCancelStatus();
            });
            payboxEvent.$on('paybox-subscription-resumed', () => {
                this.getCancelStatus();
            });
            payboxEvent.$on('paybox-subscription-resumed-and-swapped', () => {
                this.getCancelStatus();
            });
        },

        methods: {
            getCancelStatus() {
                this.$http.get('/ajax/cancelled-subscription/show')
                .then(response => {
                    this.isCancelled = response.data.isCancelled;
                    this.hasSubscription = response.data.hasSubscription;
                    this.toggleButtonState();
                })
            },

            toggleButtonState() {
                if(this.hasSubscription
                    && this.isCancelled === false
                ){
                    this.showButton = true;
                }
                if (this.isCancelled) {
                    this.showButton = false;
                }
            },

            cancelSubscription () {
                const vm = this;
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to cancel your subscription?',
                    type: 'warning',
                    showCancelButton: true,
                }).then((response) => {
                    this.loading = true;
                    vm.$http.post(`/ajax/cancelled-subscription/store`)
                    .then((response) => {
                        this.loading = false;
                        this.toggleButtonState();
                        this.successfulCancellationAlert();
                        payboxEvent.$emit('paybox-subscription-cancelled');
                    })
                    .catch(error => {
                        this.loading = false;
                        this.errorResponse(error.response.data.error);
                    });
                }).catch(() => {});
            },


            errorResponse(message) {
                this.$swal({
                    title: 'Something went wrong!',
                    text: message,
                    type: 'error'
                }).catch(() => {});
            },

            successfulCancellationAlert() {
                this.$swal({
                    title: 'success',
                    text: 'You have successfully cancelled your subscription. You are now on grace period till your subscription expires.',
                    type: 'success',
                    timer: 7000
                }).catch(() => {});
            }
        }
    }
</script>

<style scoped>
    .loader {
        margin: 5px 5px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #EE395B;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

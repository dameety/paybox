<template>
<div>
    <div class="uk-padding" v-if="visible">
        <div class="uk-background-default uk-overflow-auto uk-box-shadow-large">

           <table class="uk-table uk-table-middle uk-table-divider">
                <caption class="uk-padding-small uk-background-muted">
                    <span>
                        Payment Information
                    </span>
                </caption>
                <tbody>
                    <tr>
                        <td>
                            <span class="uk-text-bold">
                                {{cardDetails.brand}}
                            </span>
                            ending in {{cardDetails.lastFour}}
                        </td>
                        <td class="uk-text-right">
                            <button class="uk-button uk-button-small uk-button-primary"
                                @click="displayCheckoutModal()">
                                Update Details
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>

        </div>
    </div>

    <modal v-if="showModal">
        <div slot="header">
            <h2 class="uk-modal-title uk-text-left uk-margin-remove">
                Payment Information
            </h2>
        </div>

        <div slot="body">
            <div class="stripeError" v-if="stripeError">
                <p>
                    {{stripeError}}
                </p>
                <hr>
            </div>

            <form>
                <div class="uk-margin uk-text-left">
                    <label class="uk-form-label" for="Card Holder Name">
                        Card Holder Name
                    </label>
                    <div id="Card Holder Name" class="uk-form-controls">
                        <input class="uk-input" type="text"
                            v-model="card.cardHolderName"
                            name="cardHolderName"
                            @keydown="cardHolderNameError = ''"
                            :class="{ 'uk-form-danger': cardHolderNameError }">
                        <span class="help-block" v-if="cardHolderNameError">
                            {{cardHolderNameError}}
                        </span>
                    </div>
                </div>

                <div class="uk-margin uk-text-left">
                    <label class="uk-form-label" for="Card Number">
                        Card Number
                    </label>
                    <div id="Card Number" class="uk-form-controls">
                        <div id="card-number" class="uk-input"
                        :class="{ 'uk-form-danger': cardNumberError }"></div>
                        <span class="help-block" v-if="cardNumberError">
                            {{cardNumberError}}
                        </span>
                    </div>
                </div>

                <div class="uk-grid-small uk-text-left" uk-grid>
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="Card CVC">
                            Card CVC
                        </label>
                        <div id="Card CVC" class="uk-form-controls">
                            <div id="card-cvc" class="uk-input"
                                :class="{ 'uk-form-danger': cardCvcError }"></div>
                            <span class="help-block" v-if="cardCvcError">
                                {{cardCvcError}}
                            </span>
                        </div>
                    </div>
                    <div class="uk-width-1-2@s">
                        <label class="uk-form-label" for="Expiry Month">
                            Expiry
                        </label>
                        <div id="Expiry Month" class="uk-form-controls">
                            <div id="card-expiry" class="uk-input"
                                :class="{ 'uk-form-danger': cardExpiryError }"></div>
                            <span class="help-block" v-if="cardExpiryError">
                                {{cardExpiryError}}
                            </span>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div slot="footer" class="uk-align-right uk-margin-remove">
            <button class="uk-button uk-button-default"
                @click="closeCheckoutModal()">
                Close
            </button>

            <button class="uk-button uk-button-primary"
                @click="submitFormToCreateToken()">

                <div class="loader" v-if="loading"></div>

                <span v-if="! loading">
                    Update
                </span>
            </button>
        </div>
    </modal>
</div>
</template>

<script>
    import mixins from './../mixins';
    import Modal from './../modules/modal';

    export default {
        components: { Modal },

        mixins: [mixins],

        data () {
            return {
                cardDetails: {
                    brand: '',
                    lastFour: ''
                },
                visible: false
            }
        },

        created () {
            this.getSubscriptionStatus();
        },

        mounted () {
            payboxEvent.$on('paybox-subscription-created', () => {
                this.getSubscriptionStatus();
            });
            payboxEvent.$on('paybox-subscription-cancelled', () => {
                this.getSubscriptionStatus();
            });
            payboxEvent.$on('paybox-subscription-resumed', () => {
                this.getSubscriptionStatus();
            });
        },

        methods: {
            getSubscriptionStatus () {
                this.$http.get(`/ajax/payment-info/show`)
                .then((response) => {
                    this.cardDetails.brand = response.data.cardBrand;
                    this.cardDetails.lastFour = response.data.lastFour;
                    this.visible = response.data.hasSubscription;
                });
            },

            createToken() {
                const vm = this;
                this.loading = true
                this.stripe.createToken(this.cardN).then(function(result) {
                    if (result.error) {
                        this.stripeError = result.error.message;
                    } else {
                        vm.updatePaymentInfo(result.token.id);
                    }
                })
            },

            updatePaymentInfo(token) {
                const data = {stripeToken: token};
                this.$http.post(`/ajax/payment-info/update`, data)
                .then((response) => {
                    this.closeCheckoutModal();
                    this.successfulUpdateAlert();
                    payboxEvent.$emit('paybox-card-information-updated');
                })
                .catch(error => {
                    this.loading = false;
                    this.stripeError = error.response.data.error;
                });
            },

            successfulUpdateAlert() {
                setTimeout(() => {
                    this.$swal({
                        title: 'Success',
                        text: 'You have successfully updated your billing information.',
                        type: 'success',
                        timer: 3000
                    }).catch(() => { });
                }, 1000);
            }
        }
    }
</script>

<style scoped>
    #card-number {
        padding-top: 10px !important;
    }
    #card-expiry {
        padding-top: 10px !important;
    }
    #card-cvc {
        padding-top: 10px !important;
    }
    .stripeError  p {
        color: red;
        text-align: center;
    }
    span.help-block {
        font-size: 14px;
        color: red;
    }
    .loader {
        margin: 5px 5px;
        border: 5px solid #f3f3f3;
        border-top: 5px solid #0F7AE5;
        border-radius: 50%;
        width: 20px;
        height: 20px;
        animation: spin 1s linear infinite;
    }
    @keyframes spin {
        0% {transform: rotate(0deg);}
        100% {transform: rotate(360deg);}
    }
</style>

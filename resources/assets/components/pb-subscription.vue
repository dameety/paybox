<template>
    <div>
        <!-- There are five buttons in this commponent and only one is rendered on the page per time, the users subscription status is used to determien which one to show. -->

        <!-- This is the button that shows if the user has no suscription -->
        <button class="uk-button uk-button-default uk-button-small"
            @click.prevent="displayCheckoutModal()"
            :class="[isNotSubscribed, isSubscribed]"
            v-if="createSubscriptionButton">

            <div class="loader" v-if="loading"></div>

            <span v-if="!loading">
                {{wordToDisplay}}
            </span>
        </button>

        <!-- this buttons shows if the user is subscribed to
        plan passed to this component -->
        <button class="uk-button uk-button-default uk-button-small" @click.prevent="currentSubscription()"
            :class="[isSubscribed, isNotSubscribed]"
            v-if="currentPlanButton">

            <div class="loader" v-if="loading"></div>

            <span v-if="! loading">
                {{wordToDisplay}}
            </span>
        </button>

        <!-- this button shows if the user needs to resume the subscription -->
        <button class="uk-button uk-button-default uk-button-small" @click.prevent="resumeSubscription()"
            :class="[isNotSubscribed, isSubscribed]"
            v-if="resumeSubscriptionButton">

            <div class="loader" v-if="loading"></div>

            <span v-if="!loading">
                {{wordToDisplay}}
            </span>
        </button>

        <!-- this button shows if the user can swap his subscription -->
        <button class="uk-button uk-button-default uk-button-small" @click.prevent="swapSubscription()"
            :class="[isSubscribed, isNotSubscribed]" v-if="swapSubscriptionButton">

            <div class="loader" v-if="loading"></div>

            <span v-if="!loading">
                {{wordToDisplay}}
            </span>
        </button>

        <!-- this button shows so the user can resume and also swap his suscription at same time. -->
        <button class="uk-button uk-button-default uk-button-small" @click.prevent="resumeAndSwapSub()"
            :class="[isSubscribed, isNotSubscribed]" v-if="resumeAndSwapSubscriptionButton">

            <div class="loader" v-if="loading"></div>

            <span v-if="!loading">
                {{wordToDisplay}}
            </span>
        </button>

        <!-- new subscrption form -->
        <modal v-if="showModal">
            <div slot="header">
                <h2 class="uk-modal-title uk-text-left uk-margin-remove">
                    New Subscription
                </h2>
            </div>

            <div slot="body">
                <div class="stripeError" v-if="stripeError">
                    <p>
                        {{stripeError}}
                    </p>
                    <hr>
                </div>

                <!-- payment form -->
                <form>
                    <div class="uk-margin uk-text-left">
                        <label class="uk-form-label" for="Card Holder Name">
                            Card Holder Name
                        </label>
                        <div class="uk-form-controls">
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
                        <div class="uk-form-controls">
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
                            <div class="uk-form-controls">
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
                            <div class="uk-form-controls">
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
                        Submit
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

        props: {
            plan: {
                type: String
            }
        },

        data () {
            return {
                isCancelled: false,
                hasSubscription: '',
                planSubscribedTo: '',

                wordToDisplay: 'Select',

                //classes
                isSubscribed: '',
                isNotSubscribed: '',

                //buttons
                currentPlanButton: false,
                swapSubscriptionButton: false,
                createSubscriptionButton: true,
                resumeSubscriptionButton: false,
                resumeAndSwapSubscriptionButton: false
            }
        },

        created () {
            this.getSubscriptionStatus();
        },

        mounted () {
            payboxEvent.$on('paybox-subscription-swapped', () => {
                this.getSubscriptionStatus();
            });
            payboxEvent.$on('paybox-subscription-created', () => {
                this.getSubscriptionStatus();
            });
            payboxEvent.$on('paybox-subscription-resumedAndSwapped', () => {
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
                this.$http.get(`/ajax/user-subscription/${this.plan}/show`)
                .then((response) => {
                    this.isCancelled = response.data.isCancelled;
                    this.hasSubscription = response.data.hasSubscription;
                    this.planSubscribedTo = response.data.planSubscribedTo;
                    this.decideButtonToShow();
                    this.applyButtonClass();
                });
            },

            /**
             * Run different checks based on subscription
             * status, to determine which button is shown to the user
            */
            decideButtonToShow () {
                this.checkIfToShowResumeButton();
                this.checkIfToShowResumeAndSwapButton();
                this.checkIfToShowSwapButton();
                this.checkIfToShowCurrentPlanButton();
            },

            //if to show the resume Button
            checkIfToShowResumeButton() {
                if(this.isCancelled
                    && this.planSubscribedTo === this.plan) {
                    this.wordToDisplay = 'Resume'

                    this.currentPlanButton = false
                    this.swapSubscriptionButton = false
                    this.resumeSubscriptionButton = true
                    this.createSubscriptionButton = false
                    this.resumeAndSwapSubscriptionButton = false
                }
            },

            //to show resume and swap button
            checkIfToShowResumeAndSwapButton () {
                if(this.isCancelled
                    && this.planSubscribedTo !== this.plan) {
                    this.wordToDisplay = 'Select'

                    this.currentPlanButton = false
                    this.swapSubscriptionButton = false
                    this.resumeSubscriptionButton = false
                    this.createSubscriptionButton = false
                    this.resumeAndSwapSubscriptionButton = true
                }
            },

            //to show swap button
            checkIfToShowSwapButton () {
                if (! this.isCancelled
                    && this.hasSubscription
                    && this.planSubscribedTo !== this.plan) {

                    this.wordToDisplay = 'Select'

                    this.currentPlanButton = false
                    this.swapSubscriptionButton = true
                    this.resumeSubscriptionButton = false
                    this.createSubscriptionButton = false
                    this.resumeAndSwapSubscriptionButton = false
                }
            },

            //to show current plan button
            checkIfToShowCurrentPlanButton () {
                if (! this.isCancelled
                    && this.hasSubscription
                    && this.planSubscribedTo === this.plan) {

                    this.wordToDisplay = 'Current'

                    this.currentPlanButton = true
                    this.swapSubscriptionButton = false
                    this.resumeSubscriptionButton = false
                    this.createSubscriptionButton = false
                    this.resumeAndSwapSubscriptionButton = false
                }
            },

            /**
             * perform checks to choose the class to apply
            */
            applyButtonClass() {
                if (this.hasSubscription
                    && this.planSubscribedTo === this.plan) {
                    this.isNotSubscribed = ''
                    this.isSubscribed =  'uk-button-primary'
                } else {
                    this.isSubscribed = ''
                    this.isNotSubscribed = 'uk-button-default'
                }
                if(this.hasSubscription
                    && this.isCancelled
                    && this.planSubscribedTo === this.plan) {
                    this.isSubscribed = '';
                    this.isNotSubscribed = 'uk-button-secondary'
                }
            },

            currentSubscription () {
                this.$swal({
                    text: 'You are currently subscribed to this plan.',
                    type: 'success',
                    timer: 3000
                }).catch(() => {});
            },

            createToken() {
                const vm = this;
                this.loading = true
                this.stripe.createToken(this.cardN).then(function(result) {
                    if (result.error) {
                        this.stripeError = result.error.message;
                    } else {
                        vm.sendToMyServer(result.token.id);
                    }
                })
            },

            sendToMyServer (token) {
                const data = {planName: this.plan, stripeToken: token}

                this.$http.post(`/ajax/user-subscription/store`, data)
                .then((response) => {
                    this.closeCheckoutModal();
                    this.createSubscriptionSuccessAlert();
                    payboxEvent.$emit('paybox-subscription-created');
                })
                .catch(error => {
                    this.loading = false;
                    this.stripeError = error.response.data.error;
                });
            },

            swapSubscription () {
                const vm = this;
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to swap your subscription?',
                    type: 'warning',
                    showCancelButton: true,
                }).then((response) => {
                    this.loading = true;
                    vm.$http.patch(`/ajax/user-subscription/${this.plan}/update`)
                    .then((res) => {
                        this.loading = false;
                        this.swapSubscriptionSuccessAlert();
                        payboxEvent.$emit('paybox-subscription-swapped');
                    })
                    .catch(error => {
                        this.loading = false;
                        this.errorResponse(error.response.data.error);
                    });
                }).catch(() => {});
            },

            resumeAndSwapSub () {
                const vm = this;
                const data = {planName: this.plan}

                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to resume and swap your subscription?',
                    type: 'warning',
                    showCancelButton: true,
                }).then((response) => {
                    this.loading = true;
                    vm.$http.post(`/ajax/subscription/store`, data)
                    .then((res) => {
                        this.loading = false;
                        this.resumeAndSwapSubscriptionSuccessAlert();
                        payboxEvent.$emit('paybox-subscription-resumedAndSwapped');
                    })
                    .catch(error => {
                        this.loading = false;
                        this.errorResponse(error.response.data.error);
                    });
                }).catch(() => {});;
            },

            resumeSubscription () {
                const vm = this;
                this.$swal({
                    title: 'Are you sure?',
                    text: 'You are about to resume this subscription?',
                    type: 'warning',
                    showCancelButton: true,
                }).then((response) => {
                    this.loading = true;
                    vm.$http.delete(`/ajax/cancelled-subscription/destroy`)
                    .then((res) => {
                        this.loading = false;
                        this.resumeSubscriptionSuccessAlert();
                        payboxEvent.$emit('paybox-subscription-resumed');
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

            createSubscriptionSuccessAlert() {
                //wait for a second after closing the checkOutModal
                //before showing this alert
                setTimeout(() => {
                    this.$swal({
                        title: 'Success',
                        text: 'You have successfully subscribed. Thank you.',
                        type: 'success',
                        timer: 3000
                    }).catch(() => {});
                }, 800);
            },

            swapSubscriptionSuccessAlert() {
                this.$swal({
                    title: 'Success',
                    text: 'You have successfully swapped your subscription.',
                    type: 'success',
                    timer: 3000
                }).catch(() => {});
            },

            resumeAndSwapSubscriptionSuccessAlert() {
                this.$swal({
                    title: 'Success',
                    text: 'You have successfully resumed and swapped your subscription.',
                    type: 'success',
                    timer: 3000
                }).catch(() => {});
            },

            resumeSubscriptionSuccessAlert() {
                this.$swal({
                    title: 'Success',
                    text: 'Your subscription has been renewed successfully.',
                    type: 'success',
                    timer: 3000
                }).catch(() => {});
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

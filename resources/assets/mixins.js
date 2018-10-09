export default {
    data () {
        return {
            showModal: false,

            cardHolderName: '',
            card: {
                cvc: '',
                number: '',
                expiry: ''
            },

            cardN: '',
            cardE: '',
            cardC: '',
            stripe: null,

            stripeError: '',
            cardCvcError: '',
            cardExpiryError: '',
            cardNumberError: '',
            cardHolderNameError: '',

            loading: false,
        }
    },

    methods: {
        displayCheckoutModal() {
            this.showModal = true;
            setTimeout(() => {
                this.setUpStripe();
            }, 300);
        },

        closeCheckoutModal() {
            this.loading = false;
            this.showModal = false;
            this.clearInputs();
        },

        setUpStripe() {
            if (window.Stripe === undefined) {
                console.error('Stripe V3 library not loaded!')
            } else {
                const stripe = window.Stripe(Paybox.stripeKey);
                this.stripe = stripe;

                const elements = stripe.elements();
                this.cardC = elements.create('cardCvc');
                this.cardE = elements.create('cardExpiry');
                this.cardN = elements.create('cardNumber');

                this.cardC.mount('#card-cvc');
                this.cardE.mount('#card-expiry');
                this.cardN.mount('#card-number');

                this.listenForErrors();
            }
        },

        listenForErrors() {
            const vm = this;

            this.cardN.addEventListener('change', function (event) {
                vm.toggleError(event);
                vm.cardNumberError = ''
                vm.card.number = event.complete ? true : false
            });
            this.cardE.addEventListener('change', function (event) {
                vm.toggleError(event);
                vm.cardExpiryError = ''
                vm.card.expiry = event.complete ? true : false
            });
            this.cardC.addEventListener('change', function (event) {
                vm.toggleError(event);
                vm.cardCvcError = ''
                vm.card.cvc = event.complete ? true : false
            });
        },

        toggleError(event) {
            if (event.error) {
                this.stripeError = event.error.message;
            } else {
                this.stripeError = '';
            }
        },

        submitFormToCreateToken() {
            this.clearCardErrors();
            let valid = true;

            if (!this.card.cardHolderName) {
                valid = false;
                this.cardHolderNameError = "Card Holder Name is Required";
            }
            if (!this.card.number) {
                valid = false;
                this.cardNumberError = "Card Number is Required";
            }
            if (!this.card.cvc) {
                valid = false;
                this.cardCvcError = "CVC is Required";
            }
            if (!this.card.expiry) {
                valid = false;
                this.cardExpiryError = "Month is Required";
            }
            if (this.stripeError) {
                valid = false;
            }
            if (valid) {
                this.createToken();
            }
        },

        clearInputs() {
            this.cardE.clear();
            this.cardC.clear();
            this.cardN.clear();
            this.card.cardHolderName = '';
        },

        clearCardErrors() {
            this.stripeError = '';
            this.cardCvcError = '';
            this.cardExpiryError = '';
            this.cardNumberError = '';
            this.cardHolderNameError = '';
        }

    }
}

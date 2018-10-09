<template>

    <modal v-if="showModal">
        <div slot="header">
            <h2 class="uk-modal-title uk-margin-remove">
                User Invoice
            </h2>
        </div>

        <div slot="body">

            <div class="loader uk-align-center" v-if="loading"></div>

            <div class="uk-modal-body uk-padding-remove" v-if="! loading">

                <div class="uk-text-center bg-danger" v-if="stripeError">
                    <p class="uk-text-small uk-padding uk-margin-remove">
                        {{stripeError}}
                    </p>
                </div>

                <div>
                    <table class="uk-table uk-table-middle uk-table-striped">
                        <thead>
                            <tr>
                                <th class="uk-text-center uk-table-shrink">date</th>
                                <th class="uk-text-center uk-table-shrink">total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr v-for="(invoice, index) in invoices">
                                <td class="uk-text-center">
                                    <p class="uk-text-meta uk-text-small">
                                        {{invoice.date}}
                                    </p>
                                </td>
                                <td class="uk-text-center">
                                    <p class="uk-text-meta uk-text-small">
                                        {{invoice.total}}
                                    </p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <div slot="footer" class="uk-align-right uk-margin-remove">
            <button class="uk-button uk-button-default"
                @click="closeModal()">
                Close
            </button>
        </div>
    </modal>

</template>

<script>
    import Modal from './../../modules/modal';

    export default {
        components: {
            Modal
        },

        data () {
            return {
                invoices: [],
                loading: false,
                stripeError: '',
                userId: this.$root.$data.userId,
                showModal: this.$root.$data.userInvoicesModal,
            }
        },

        created () {
            this.getInvoices();
        },

        methods: {
            getInvoices() {
                this.loading = true;
                this.$http.get(`/ajax/invoice/${this.userId}/show`)
                .then((response) => {
                    this.invoices = response.data.invoices;
                    this.loading = false;
                })
                .catch((error) => {
                    this.loading = false;
                    this.stripeError = error.response.data.error
                });
            },

            closeModal () {
                this.showModal = false;
                this.$root.$data.userInvoicesModal = false;
            },
        }
    }
</script>

<style scoped>
    .loader {
        margin: auto;
        border: 10px solid #f3f3f3;
        border-top: 10px solid #000000;
        border-radius: 50%;
        width: 100px;
        height: 100px;
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

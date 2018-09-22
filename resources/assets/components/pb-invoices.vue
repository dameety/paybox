<template>
<div>

    <!-- to view invoice by current auth user -->
    <div class="uk-padding" v-if="invoices.length > 0">
        <div class="uk-background-default uk-overflow-auto uk-box-shadow-large">

            <table class="uk-table uk-table-middle uk-table-divider">
                 <caption class="uk-padding-small uk-background-muted">
                    All Invoices
                </caption>
                <thead>
                    <tr>
                        <th>Date</th>
                        <th class="uk-text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(invoice, index) in invoices">
                        <td>
                            <p>
                                {{invoice.date}}
                            </p>
                        </td>
                        <td class="uk-text-right">
                            <p>
                                {{invoice.total}}
                            </p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- if invoices could not be loaded -->
    <div class="uk-padding" v-if="stripeError">
        <div class="uk-background-danger uk-overflow-auto uk-box-shadow-large">
            <p class="stripeError uk-padding uk-margin-remove">
                {{stripeError}}
            </p>
        </div>
    </div>

</div>
</template>

<script>
    export default {
        data () {
            return {
                invoices: [],
                stripeError: '',
            }
        },

        created () {
            this.getInvoices();
        },

        mounted() {
            payboxEvent.$on('paybox-subscription-swapped', () => {
                this.getInvoices();
            });
            payboxEvent.$on('paybox-subscription-created', () => {
                this.getInvoices();
            });
            payboxEvent.$on('paybox-subscription-cancelled', () => {
                this.getInvoices();
            });
            payboxEvent.$on('paybox-subscription-resumedAndSwapped', () => {
                this.getInvoices();
            });
            payboxEvent.$on('paybox-subscription-resumed', () => {
                this.getInvoices();
            });
        },

        methods: {
            getInvoices() {
                this.$http.get('/ajax/invoices/show')
                .then((response) => {
                    this.invoices = response.data.invoices
                })
                .catch((error) => {
                    this.stripeError = 'Could not load invoices, please try again.';
                    console.error(error.response.data);
                });
            }
        }
    }
</script>

<style scoped>
    .stripeError {
        color: #a00f0f;
        font-style: italic;
        font-size: 14px;
    }
</style>
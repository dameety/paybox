<template>
<div class="uk-width-xxlarge uk-align-center"
    v-if="!fetchingData">

    <div class="uk-padding" v-if="plans.length > 0">
        <div class="uk-background-default uk-overflow-auto uk-box-shadow-large">
            <table class="uk-table uk-table-middle uk-table-divider">

                <caption class="uk-padding-small uk-background-muted">
                    <span>
                        All Plans
                    </span>
                </caption>

                <thead>
                    <tr>
                        <th>Name</th>
                        <th class="uk-text-center">Price</th>
                        <th class="uk-text-center">Features</th>
                        <th class="uk-text-right"></th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="(plan, index) in plans">
                        <td>
                            {{plan.name}}
                        </td>
                        <td class="uk-text-center">
                            {{symbol}}{{plan.amount / 100}} / {{plan.interval}}
                        </td>
                        <td class="uk-text-center">
                            <pb-plan-features
                            :features="plan.features"
                            ></pb-plan-features>
                        </td>
                        <td class="uk-text-right">
                            <pb-subscription
                                :plan="plan.name"
                            ></pb-subscription>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td class="uk-table-shrink uk-text-right">
                            <pb-cancel-subscription>
                            </pb-cancel-subscription>
                        </td>
                    </tr>
                </tbody>

            </table>
        </div>

    </div>

    <pb-payment-information></pb-payment-information>

    <pb-invoices></pb-invoices>

</div>
</template>

<script>
    export default {
        data () {
            return {
                plans: [],
                stripeError: '',
                symbol: Paybox.symbol,
                fetchingData: true,
            }
        },

        created() {
            this.getPlans();
        },

        methods: {
            getPlans() {
                this.$http.get('/ajax/plans')
                .then((response) => {
                    this.plans = response.data.plans
                    this.fetchingData = false;
                })
                .catch((error) => {
                    console.error(error.response.data.error);
                });
            }
        }
    }
</script>

<style scoped>

</style>
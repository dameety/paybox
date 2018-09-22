<template>

    <modal v-if="showModal">
        <div slot="header">
            <h2 class="uk-modal-title uk-margin-remove">
                Plan Features
            </h2>
        </div>

        <div slot="body">
            <div class="uk-modal-body uk-padding-remove">
                <p>
                    {{planFeatures}}
                </p>
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
                showModal: this.$root.$data.showPlanFeaturesModal,
                planFeatures: this.$root.$data.planFeatures
            }
        },

        methods: {
            closeModal () {
                this.showModal = false;
                this.$root.$data.showPlanFeaturesModal = false;
            },
        }

    }
</script>
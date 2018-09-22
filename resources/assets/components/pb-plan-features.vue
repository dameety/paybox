<template>
<div>

    <modal v-if="showModal">
        <div slot="header">
            <h2 class="uk-modal-title uk-text-left uk-margin-remove">
                Plan Features
            </h2>
        </div>

        <div slot="body">
            <div class="uk-modal-body uk-text-left uk-padding-remove">
                <p>
                    {{planFeatures}}
                </p>
            </div>
        </div>

        <div slot="footer" class="uk-align-right uk-margin-remove">
            <button class="uk-button uk-button-default"
                @click="close()">
                Close
            </button>
        </div>
    </modal>

    <button class="uk-button uk-button-default uk-button-small"
        @click.prevent="show()">
        view
    </button>

</div>
</template>

<script>
    import Modal from './../modules/modal';

    export default {
        props: {
            features: {
                type: String,
                default: ''
            }
        },

        components: { Modal },

        data () {
            return {
                showModal: false,
                planFeatures: this.features
            }
        },

        methods: {
            show () {
                this.showModal = true;
            },

            close () {
                this.showModal = false;
            },
        }

    }
</script>
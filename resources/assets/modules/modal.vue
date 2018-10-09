<template>
    <div uk-modal class="uk-flex-top uk-open" style="display: block">

        <div class="uk-modal-dialog" @click.stop>
            <div class="uk-modal-header">
                <slot name="header"></slot>
            </div>

            <div class="uk-modal-body">
                <slot name="body"></slot>
            </div>

            <div class="uk-modal-footer uk-background-muted">
                <div class="modal-footer">
                    <slot name="footer"></slot>
                </div>
            </div>
        </div>

    </div>
</template>

<style>
    .uk-modal-body {
        padding: 15px;
    }
</style>
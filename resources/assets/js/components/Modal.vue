<template>
    <div class="modal modal-component" :class="statusClass" :style="statusStyle" data-animation="false" data-keyboard="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <slot name="header"></slot>
                    <button v-on:click="close" v-on:keyup.esc="close" type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <slot></slot>
                </div>
                <slot name="footer"></slot>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        watch: {
            status: function (n, o) {
                if (n !== o) {
                    $(this.$el).modal('toggle');
                }
            }
        },
        mounted() {
            $(this.$el).modal({
                backdrop: 'static',
                keyboard: false,
                show: false
            });
        },
        computed: {
            statusClass() {
                return this.status ? 'show' : 'hide';
            },
            statusStyle() {
                return { 'display': this.status ? 'block' : 'none' };
            }
        },
        props: {
            'status': {'type': Boolean, 'default': false}
        },
        methods: {
            close() {
                $(this.$el).modal('hide');
                this.$emit('close');
            }
        }
    }
</script>

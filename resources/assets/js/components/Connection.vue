<template>
    <a class="nav-link nav-item nav-connection d-flex p-1 flex-row-reverse" :class="className" href="#" @click="toggle">
        <i class="d-inline-flex mdi" :class="icon"></i>
        <span class="d-inline-flex align-items-center" v-text="label"></span>
    </a>
</template>

<script>
    export default {
        computed: {
            className() {
                return this.status ? 'is-connected' : 'is-disconnected';
            },
            icon() {
                return this.status ? 'mdi-power' : 'mdi-refresh';
            },
            label() {
                return this.status ? 'Disconnect' : 'Reconnect';
            }
        },
        props: ['status'],
        methods: {
            toggle(e) {
                e.preventDefault();
                Event.fire('connection.toggle');
            }
        }
    }
</script>
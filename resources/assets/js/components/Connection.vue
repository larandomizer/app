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
                return this.connected ? 'is-connected' : 'is-disconnected';
            },
            icon() {
                return this.connected ? 'mdi-power' : 'mdi-refresh';
            },
            label() {
                return this.connected ? 'Disconnect' : 'Reconnect';
            }
        },
        props: ['connection', 'connected'],
        methods: {
            toggle(e) {
                e.preventDefault();
                Event.fire('connection.toggle', this.connection);
            }
        }
    }
</script>

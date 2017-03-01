<template>
    <a class="nav-link nav-item nav-user d-flex p-1" :class="className" href="#">
        <i class="d-inline-flex mdi mdi-account-circle"></i>
        <span class="d-inline-flex align-items-center" v-text="name" v-if="connected"></span>
        <span class="d-inline-flex align-items-center" v-if="!connected">Not connected</span>
    </a>
</template>

<script>
    export default {
        computed: {
            name() {
                return this.connection && this.connection.name !== undefined ? this.connection.name : 'Anonymous';
            },
            className() {
                return this.connected ? 'is-connected' : 'is-disconnected';
            }
        },
        props: ['connection', 'connected']
    }
</script>

<template>
    <span class="nav-link nav-item nav-user d-flex p-1" :class="className">
        <i class="d-inline-flex mdi mdi-account-circle"></i>
        <span class="d-inline-flex align-items-center" v-text="connection.name || 'Anonymous'" v-if="connected"></span>
        <span class="d-inline-flex align-items-center" v-if="!connected">Not connected</span>
    </span>
</template>

<script>
    export default {
        computed: {
            className() {
                return this.connected ? 'is-connected' : 'is-disconnected';
            }
        },
        props: ['connection', 'connected']
    }
</script>

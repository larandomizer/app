<template>
    <a class="nav-link nav-item nav-user d-flex p-1" :class="status" href="#">
        <i class="d-inline-flex mdi mdi-account-circle"></i>
        <span class="d-inline-flex align-items-center" v-text="user.name"></span>
    </a>
</template>

<script>
    export default {
        computed: {
            status() {
                return this.connection ? 'is-connected' : 'is-disconnected';
            }
        },
        props: ['user', 'connection']
    }
</script>
<template>
    <div class="dropdown nav-notifications" v-if="isConnected">
        <a href="#notifications" class="nav-link nav-item btn-dropdown d-flex p-1" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi d-inline-flex align-items-center mdi-bell"></i>
            <span class="d-inline-flex align-items-center nav-notifications-count">{{ messageCount }}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="notificationButton">
            <a class="dropdown-item d-flex" href="#">
                <span class="d-inline-flex align-items-center">You have {{ messageCount }} messages</span>
                <i class="mdi dropdown-icon mdi-eye align-items-end ml-auto"></i>
            </a>
            <a class="dropdown-item d-flex" href="#" v-for="n in messages">
                <i class="mdi mdi-account-circle nav-notification-icon"></i>
                <span class="d-inline-flex align-items-center">{{ n.from }}</span>
                <span class="d-inline-flex align-items-center ml-auto nav-notification-time">{{ ago(n) }}</span>
            </a>
        </div>
    </div>
</template>

<script>
    import * as moment from 'moment';

    export default {
        props: ['messages', 'status'],
        computed: {
            messageCount() {
                return this.messages.length;
            },
            isConnected() {
                return this.status;
            },
        },
        methods: {
            ago(message) {
                return moment(message.created_at, moment.ISO_8601).fromNow()
            }
        }
    }
</script>

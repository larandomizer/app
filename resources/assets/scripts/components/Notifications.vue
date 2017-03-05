<template>
    <div class="dropdown nav-notifications" v-if="isConnected">
        <a href="#notifications" class="nav-link nav-item btn-dropdown d-flex p-1" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi d-inline-flex align-items-center mdi-bell"></i>
            <span class="nav-notifications-count" v-if="count">{{ count }}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="notificationButton">
            <div class="dropdown-item d-flex" href="#">
                <span class="d-inline-flex align-items-center text-center">You have {{ count || 'no new' }} notifications</span>
                <a class="mdi dropdown-icon mdi-eye align-items-end ml-auto" v-if="count" href="#" @click="dismissAllNotifications"></a>
            </div>
            <div class="nav-notifications-list" v-if="count">
                <div class="dropdown-item d-flex" href="#" v-for="notification in notifications">
                    <i class="mdi mdi-account-circle nav-notification-icon"></i>
                    <span class="d-inline-flex align-items-center">{{ senderName(notification) }}</span>
                    <span class="d-inline-flex align-items-center ml-auto nav-notification-time">{{ ago(notification.timestamp) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as moment from 'moment';

    export default {
        props: ['connections', 'notifications', 'connected'],
        computed: {
            count() {
                return this.notifications.length;
            },
            isConnected() {
                return this.connected;
            },
        },
        methods: {
            ago(timestamp) {
                return moment.unix(timestamp).fromNow();
            },
            dismissAllNotifications(e) {
                e.preventDefault();
                Event.fire('DismissAllNotifications');
            },
            sender(notification) {
                return _.find(this.connections, {
                    uuid: notification.sender
                });
            },
            senderName(notification) {
                let sender = this.sender(notification);
                return  sender ? sender.name : 'Unknown';
            },
        }
    }
</script>

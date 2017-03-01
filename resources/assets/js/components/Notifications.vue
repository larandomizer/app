<template>
    <div class="dropdown nav-notifications" v-if="isConnected">
        <a href="#notifications" class="nav-link nav-item btn-dropdown d-flex p-1" id="notificationButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="mdi d-inline-flex align-items-center mdi-bell"></i>
            <span class="d-inline-flex align-items-center nav-notifications-count" v-if="notificationsCount">{{ notificationsCount }}</span>
        </a>
        <div class="dropdown-menu" aria-labelledby="notificationButton">
            <div class="dropdown-item d-flex" href="#">
                <span class="d-inline-flex align-items-center text-center">You have {{ notificationsCount || 'no new' }} notifications</span>
                <a class="mdi dropdown-icon mdi-eye align-items-end ml-auto" v-if="notificationsCount" href="#" @click="dismissAllNotifications"></a>
            </div>
            <div class="nav-notifications-list" v-if="notificationsCount">
                <div class="dropdown-item d-flex" href="#" v-for="notification in notifications">
                    <i class="mdi mdi-account-circle nav-notification-icon"></i>
                    <span class="d-inline-flex align-items-center">{{ notification.sender }}</span>
                    <span class="d-inline-flex align-items-center ml-auto nav-notification-time">{{ ago(notification) }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import * as moment from 'moment';

    export default {
        props: ['notifications', 'connected'],
        computed: {
            notificationsCount() {
                return this.notifications.length;
            },
            isConnected() {
                return this.connected;
            },
        },
        methods: {
            ago(notification) {
                return moment(notification.created_at, moment.ISO_8601).fromNow()
            },
            dismissAllNotifications(e) {
                e.preventDefault();
                Event.fire('notification.dismiss.all');
            }
        }
    }
</script>

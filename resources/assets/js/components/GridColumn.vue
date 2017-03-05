<template>
    <td v-if="id === 'name'">
        <span class="d-flex align-content-center no-wrap">
            <i class="mdi mdi-account-circle d-inline-flex pr-2" :class="connectionClass"></i>
            <span class="d-inline-flex">{{ record[id] || 'Anonymous' }}</span>
            <a class="connection-ping" @click="notify" v-show="showNotifier" v-if="!active"><i class="mdi mdi-bell"></i></a>
        </span>
    </td>
    <td v-else-if="id === 'email'" class="no-wrap">
        <span>{{ record[id] || 'Not available' }}</span>
    </td>
    <td v-else-if="id === 'timestamp'" width="50">
        <span class="d-flex align-content-center">
            <i class="mdi mdi-clock d-inline-flex pr-2" data-toggle="tooltip" :title="ago(record[id])"></i>
        </span>
    </td>
    <td v-else-if="id === 'type'">
        <span class="badge badge-block" :class="typeClass">{{ record[id] }}</span>
    </td>
    <td v-else-if="id === 'uuid'">
        <span class="no-wrap">{{ record[id] }}</span>
    </td>
    <td v-else>{{ record[id] }}</td>
</template>

<script>
    import * as moment from 'moment';

    let typeMap = {
        'winner': 'badge-warning',
        'anonymous': 'badge-info',
        'loser': 'badge-inverse',
        'player': 'badge-success',
        'spectator': 'badge-default'
    };
    export default {
        created() {
            Event.listen('grid.row.on', row => {
                if (row.uuid === this.record.uuid) {
                    this.showNotifier = true;
                }
            });
            Event.listen('grid.row.off', row => {
                this.showNotifier = false;
            });
        },
        mounted() {
            $('[data-toggle=tooltip]', this.$el).tooltip();
        },
        computed: {
            typeClass() {
                return typeMap[this.record.type];
            },
            connectionClass() {
                return this.active === true ? 'text-primary' : 'text-info';
            }
        },
        data() {
            return {
                showNotifier: false
            }
        },
        props: {
            'record': {type: Object, required: true},
            'id': {type: String, required: true},
            'active': {type: Boolean, 'default': false }
        },
        methods: {
            ago(timestamp) {
                return moment.unix(timestamp).fromNow();
            },
            notify(e) {
                e.preventDefault();
                Event.fire('NotificationSend', this.record);
            }
        }
    }
</script>

<template>
    <td v-if="id === 'name'" width="250">
        <span class="d-flex align-content-center">
            <i class="mdi mdi-account-circle d-inline-flex pr-2" :class="connectionClass"></i>
            <span class="d-inline-flex">{{ record[id] || 'Anonymous' }}</span>
            <a class="connection-ping" @click="notify" v-show="showNotifier" v-if="!active"><i class="mdi mdi-bell"></i></a>
        </span>
    </td>
    <td v-else-if="id === 'email'">
        <span>{{ record[id] || 'Not available' }}</span>
    </td>
    <td v-else-if="id === 'timestamp'">
        <span class="d-flex align-content-center">
            <i class="mdi mdi-clock d-inline-flex pr-2"></i>
            <span class="d-inline-flex">{{ record[id] }}</span>
        </span>
    </td>
    <td v-else-if="id === 'type'">
        <span class="badge badge-block" :class="typeClass">{{ record[id] }}</span>
    </td>
    <td v-else>{{ record[id] }}</td>
</template>

<script>
    let typeMap = {
        'winner': 'badge-warning',
        'anonymous': 'badge-info',
        'loser': 'badge-inverse',
        'player': 'badge-success',
        'spectator': 'badge-default'
    };
    let connectionMap = {
        'winner': 'text-warning',
        'anonymous': 'text-inverse',
        'loser': 'text-primary',
        'player': 'text-primary',
        'spectator': 'text-info'
    };
    export default {
        created() {
            Event.listen('grid.row.on', row => {
                if (row.uuid === this.record.uuid) {
                    this.showNotifier = true;
                }
            });
            Event.listen('grid.row.off', row => {
                if (row.uuid === this.record.uuid) {
                    this.showNotifier = false;
                }
            });
        },
        computed: {
            typeClass() {
                return typeMap[this.record.type]
            },
            connectionClass() {
                if (this.active === true) {
                    return 'text-primary';
                }
                return connectionMap[this.record.type]
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
            notify(e) {
                e.preventDefault();
                Event.fire('notification.send', this.record);
            }
        }
    }
</script>

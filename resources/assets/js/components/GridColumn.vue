<template>
    <td width="300" v-if="id === 'name'">
        <i class="mdi mdi-account-circle" :class="connectionClass"></i> {{ record[id] }}
        <a class="connection-ping" @click="ping" v-show="showPinger" v-if="!current"><i class="mdi mdi-bell"></i></a>
    </td>
    <td v-else-if="id === 'timestamp'"><i class="mdi mdi-clock"></i> {{ record[id] }}</td>
    <td v-else-if="id === 'status'"><span class="badge badge-block" :class="statusClass">{{ record[id] }}</span></td>
    <td v-else>{{ record[id] }}</td>
</template>

<script>
    let statusMap = {
        'winner': 'badge-warning',
        'waiting': 'badge-info',
        'loser': 'badge-inverse',
        'ready': 'badge-success',
        'spectator': 'badge-default'
    };
    let connectionMap = {
        'current': 'text-primary',
        'anonymous': 'text-info',
        'regular': 'text-inverse'
    };
    export default {
        created() {
            Event.listen('grid.row.on', row => {
                if (row.player_id === this.record.player_id) {
                    this.showPinger = true;
                }
            });
            Event.listen('grid.row.off', row => {
                if (row.player_id === this.record.player_id) {
                    this.showPinger = false;
                }
            });
        },
        computed: {
            statusClass() {
                return statusMap[this.record.status]
            },
            connectionClass() {
                let type = 'regular';
                if (this.record.status === 'spectator') type = 'anonymous';
                if (this.current) type = 'current';
                return connectionMap[type]
            }
        },
        data() {
            return {showPinger: false}
        },
        props: {
            'record': {type: Object, required: true},
            'id': {type: String, required: true},
            'current': {type: Boolean, 'default': false }
        },
        methods: {
            ping() {
                Event.fire('connection.ping', this.record);
            }
        }
    }
</script>
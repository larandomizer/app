<template>
    <td width="300" v-if="id === 'name'">
        <i class="mdi mdi-account-circle" :class="connectionClass"></i> {{ record[id] }}
        <a class="connection-ping" @click="notify" v-show="showNotifier" v-if="!active"><i class="mdi mdi-bell"></i></a>
    </td>
    <td v-else-if="id === 'timestamp'"><i class="mdi mdi-clock"></i> {{ record[id] }}</td>
    <td v-else-if="id === 'type'"><span class="badge badge-block" :class="typeClass">{{ record[id] }}</span></td>
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
        'winner': 'text-primary',
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
                return connectionMap[this.record.type]
            }
        },
        data() {
            return {showNotifier: false}
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

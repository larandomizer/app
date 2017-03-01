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
                let name = 'Anonymous';

                if (this.connection.hasOwnProperty('name')) {
                    this.formatName(this.connection.name);
                }

                return name;
            },
            className() {
                return this.connected ? 'is-connected' : 'is-disconnected';
            }
        },
        props: ['connection', 'connected'],
        methods: {
            formatName(name) {
                let names = [];

                if (name !== undefined) {
                    names = name.split(' ');
                    name = names[0];
                    if (names[1]) {
                        name += ' ' + names[1][1].toUpperCase() + '.';
                    }
                }

                return name;
            }
        }
    }
</script>

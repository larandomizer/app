<template>
    <a class="dropdown-item d-flex" :class="nameClass" :href="link" @click="click">
        <i class="mdi dropdown-icon" :class="iconClass"></i>
        <span class="d-inline-flex align-items-center" v-text="title"></span>
    </a>
</template>

<script>
    export default {
        computed: {
            nameClass() {
                return 'dropdown-item-' + this.anchor;
            },
            iconClass() {
                return 'mdi-' + this.icon;
            },
            link() {
                return '#' + this.anchor;
            },
            anchor() {
                return this.event.replace(/[\.\_]+/g, '-').toLowerCase();
            }
        },
        props: ['event', 'icon', 'title'],
        methods: {
            click(e) {
                e.preventDefault();
                Event.fire(this.event);
            }
        }
    }
</script>

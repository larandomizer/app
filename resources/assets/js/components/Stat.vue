<template>
    <div class="card card-stat" :class="nameClass">
        <div class="card-content">
            <i class="mdi card-icon" :class="iconClass"></i>
            <div class="card-details">
                <h4 class="card-title" v-text="title"></h4>
                <h6 class="card-subtitle" v-text="subtitle"></h6>
            </div>
        </div>
        <stat-dropdown :items="menuItems" v-if="menuItems"></stat-dropdown>
    </div>
</template>

<script>
    export default {
        computed: {
            nameClass() {
                return 'card-stat-' + this.name
            },
            iconClass() {
                return 'mdi-' + this.icon
            }
        },
        props: ['name', 'icon', 'title', 'subtitle', 'menuItems']
    }
</script>

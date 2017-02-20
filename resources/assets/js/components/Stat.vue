<template>
    <div class="card card-stat" :class="nameClass">
        <div class="card-content">
            <i class="mdi card-icon" :class="iconClass"></i>
            <div class="card-details">
                <h4 class="card-title" v-text="title"></h4>
                <h6 class="card-subtitle" v-text="subtitle"></h6>
            </div>
        </div>
        <div class="dropdown card-dropdown">
            <button class="btn btn-link btn-dropdown" type="button" id="dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mdi mdi-dots-horizontal"></span>
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownButton">
                <slot></slot>
            </div>
        </div>
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
        props: ['name', 'icon', 'title', 'subtitle'],
        mounted() {

        }
    }
</script>

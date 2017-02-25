<template>
    <div class="dropdown card-dropdown">
        <button class="btn btn-link btn-dropdown" type="button" id="dropdownButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span class="mdi mdi-dots-horizontal"></span>
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownButton">
            <stat_dropdown_item v-for="item in items" :icon="item.icon" :event="item.event" :title="item.title"></stat_dropdown_item>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['items']
    }
</script>
